<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use App\Service\Telegram\Methods\GetChatMembersCount;
use App\Service\Telegram\Methods\GetMe;
use App\Service\Telegram\Methods\GetWebhookInfo;
use App\Service\Telegram\Methods\SendMessage;
use App\Service\Telegram\Methods\SetWebhook;
use App\Service\Telegram\Methods\TelegramMethods;
use App\Service\Telegram\Types\Message;
use App\Service\Telegram\Types\Update;
use App\Service\Telegram\Types\User;
use App\Service\Telegram\Types\WebhookInfo;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Twig\Environment as Twig;

final class Bot
{
    private TelegramAPI $telegramAPI;
    private Twig $twig;

    public function __construct(TelegramAPI $telegramAPI, Twig $twig)
    {
        $this->telegramAPI = $telegramAPI;
        $this->twig        = $twig;
    }

    public function getMe(): ?User
    {
        $getMe = new GetMe();

        $response = $this->telegramAPI->makeRequest($getMe);
        $json     = \json_decode($response->getContent(), true);

        if (!isset($json['ok']) || (isset($json['ok']) && $json['ok'] !== true)) {
            return null;
        }

        return new User($json['result']);
    }

    public function setWebhook(string $url): ?WebhookInfo
    {
        $method = new SetWebhook($url);

        $response = $this->telegramAPI->makeRequest($method);
        $json     = \json_decode($response->getContent(), true);

        if (!isset($json['ok']) || (isset($json['ok']) && $json['ok'] !== true)) {
            return null;
        }

        return $this->getWebhookInfo();
    }

    public function getWebhookInfo(): WebhookInfo
    {
        $method = new GetWebhookInfo;

        $response = $this->telegramAPI->makeRequest($method);
        $json     = \json_decode($response->getContent(), true);

        if (!isset($json['ok']) || (isset($json['ok']) && $json['ok'] !== true)) {
            return null;
        }

        return new WebhookInfo($json['result']);
    }

    public function request(TelegramMethods $method): ResponseInterface
    {
        return $this->telegramAPI->makeRequest($method);
    }

    public function answer(array $update): Message
    {
        $update = new Update($update);
        if (!$this->isValid($update)) {
            return null;
        }

        $message = $update->editedMessage ?? $update->message;
        $chat    = $message->chat;

        $chatCountRequest = $this->telegramAPI->makeRequest(new GetChatMembersCount($chat->id));
        $totalMembers = \json_decode($chatCountRequest->getContent());

        $text = $this->twig->render('Update/infamous_hideous_list.html.twig', [
            'members'         => $totalMembers,
            'members_on_line' => mt_rand($totalMembers, $totalMembers * time()),
        ]);

        $answer = new SendMessage($chat->id, $message->messageId, $text);

        $response = $this->telegramAPI->makeRequest($answer);

        return new Message(\json_decode($response, true));
    }

    private function isValid(Update $update): bool
    {
        $message = $update->editedMessage ?? $update->message;
        if (!in_array($message->chat->type, array('group', 'supergroup'), true)) {
            return false;
        }

        if (!preg_match('/^[1-9]\d* mensaje+s{0,1}/', $message->text, $matches)) {
            return false;
        }

        return true;
    }
}
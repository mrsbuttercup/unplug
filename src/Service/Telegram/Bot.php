<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use App\Service\Telegram\Methods\DeleteWebhook;
use App\Service\Telegram\Methods\GetChatMembersCount;
use App\Service\Telegram\Methods\GetMe;
use App\Service\Telegram\Methods\GetUpdates;
use App\Service\Telegram\Methods\GetWebhookInfo;
use App\Service\Telegram\Methods\SendMessage;
use App\Service\Telegram\Methods\SetWebhook;
use App\Service\Telegram\Types\Message;
use App\Service\Telegram\Types\Update;
use App\Service\Telegram\Types\User;
use App\Service\Telegram\Types\WebhookInfo;
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
        if (!isset($response['ok']) || (isset($response['ok']) && $response['ok'] !== true)) {
            return null;
        }

        return new User($response['result']);
    }

    public function setWebhook(string $url): void
    {
        $method = new SetWebhook($url);

        $this->telegramAPI->makeRequest($method);
    }

    public function removeWebhook(): void
    {
        $method = new DeleteWebhook();

        $this->telegramAPI->makeRequest($method);
    }

    public function getWebhookInfo(): WebhookInfo
    {
        $method = new GetWebhookInfo;

        $response = $this->telegramAPI->makeRequest($method);
        if (!isset($response['ok']) || (isset($response['ok']) && $response['ok'] !== true)) {
            return null;
        }

        return new WebhookInfo($response['result']);
    }

    /**
     * @see https://core.telegram.org/bots/api#getupdates
     */
    public function cleanPendingUpdates(int $maxAttempts = 3): void
    {
        $webhookInfo = $this->getWebhookInfo();
        $attemptsCounter = 0;

        $currentWebhookUrl = $webhookInfo->url;

        // we need to disable webhook url before getting updates...
        $this->removeWebhook();

        while ($webhookInfo->pendingUpdateCount > 0 && $attemptsCounter < $maxAttempts) {
            $lastUpdate = $this->getLastUpdate();
            if ($lastUpdate) {
                $biggerOffset = $lastUpdate->updateId + 1;

                $this->telegramAPI->makeRequest(new GetUpdates(1, $biggerOffset));
            }

            $webhookInfo = $this->getWebhookInfo();

            $maxAttempts++;
        }

        // Restore webhook url
        $this->setWebhook($currentWebhookUrl);
    }

    public function getUpdates(): array
    {
        $method = new GetUpdates();
        $response = $this->telegramAPI->makeRequest($method);
        if (!isset($response['ok']) || (isset($response['ok']) && $response['ok'] !== true)) {
            return null;
        }

        $updates = $response['result'];

        return array_map(function ($update) {
            return new Update($update);
        }, $updates);
    }

    public function getLastUpdate(): ?Update
    {
        $method = new GetUpdates(1);
        $response = $this->telegramAPI->makeRequest($method);
        if (!isset($response['ok']) || (isset($response['ok']) && $response['ok'] !== true)) {
            return null;
        }

        $updates = $response['result'];
        if (!count($updates)) {
            return null;
        }

        [$lastUpdate] = $updates;

        return new Update($lastUpdate);
    }

    public function sendAnswer(array $update): ?Message
    {
        $update = new Update($update);
        if (!$this->isValid($update)) {
            return null;
        }

        $message = $update->editedMessage ?? $update->message;
        $chat    = $message->chat;

        $response = $this->telegramAPI->makeRequest(new GetChatMembersCount($chat->id));
        if (!isset($response['ok']) || (isset($response['ok']) && $response['ok'] !== true)) {
            return null;
        }

        $totalMembers = $response['result'];

        $text = $this->twig->render('infamous_hideous_list.html.twig', [
            'members'         => $totalMembers,
            'members_on_line' => \mt_rand($totalMembers, $totalMembers * time()),
        ]);

        $answer = new SendMessage($chat->id, $message->messageId, $text);

        $response = $this->telegramAPI->makeRequest($answer);
        if (!isset($response['ok']) || (isset($response['ok']) && $response['ok'] !== true)) {
            return null;
        }

        return new Message($response['result']);
    }

    private function isValid(Update $update): bool
    {
        $message = $update->editedMessage ?? $update->message;
        if (!$message->text) {
            return false;
        }

        if (!\in_array($message->chat->type, array('group', 'supergroup'), true)) {
            return false;
        }

        if (!\preg_match('/^[0-9]\d* mensaje+s{0,1}$/', $message->text, $matches)) {
            return false;
        }

        return true;
    }
}
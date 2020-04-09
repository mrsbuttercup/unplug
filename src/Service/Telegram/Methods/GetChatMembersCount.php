<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

/**
 * @see https://core.telegram.org/bots/api#getchatmemberscount
 */
final class GetChatMembersCount extends TelegramMethods implements \JsonSerializable
{
    private const METHOD = 'POST';

    private int $chatId;

    public function __construct(int $chatId)
    {
        $this->chatId = $chatId;
    }

    public function getMethod(): string
    {
        return self::METHOD;
    }

    public function jsonSerialize()
    {
        return [
            'chat_id' => $this->chatId,
        ];
    }
}
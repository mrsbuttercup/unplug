<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

final class GetChatMember extends TelegramMethods implements \JsonSerializable
{
    private const METHOD = 'POST';

    public string $chatId;
    public string $userId;

    public function __construct(string $chatId, string $userId)
    {
        $this->chatId = $chatId;
        $this->userId = $userId;
    }

    public function getMethod(): string
    {
        return self::METHOD;
    }

    public function jsonSerialize()
    {
        return [
            'chat_id' => $this->chatId,
            'user_ud' => $this->userId,
        ];
    }
}
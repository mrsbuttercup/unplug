<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

final class GetChatMember extends TelegramMethods implements \JsonSerializable
{
    public string $chatId;
    public int $userId;

    public function __construct(string $chatId, int $userId)
    {
        $this->chatId = $chatId;
        $this->userId = $userId;
    }

    public function jsonSerialize()
    {
        return [
            'chat_id' => $this->chatId,
            'user_ud' => $this->userId,
        ];
    }
}
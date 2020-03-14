<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

final class GetChatMembersCount extends TelegramMethods implements \JsonSerializable
{
    private int $chatId;

    public function __construct(int $chatId)
    {
        $this->chatId = $chatId;
    }

    public function jsonSerialize()
    {
        return [
            'chat_id' => $this->chatId,
        ];
    }
}
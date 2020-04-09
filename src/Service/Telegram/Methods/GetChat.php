<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

final class GetChat extends TelegramMethods implements \JsonSerializable
{
    private const METHOD = 'POST';

    public string $chatId;

    public function __construct(string $chatId)
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
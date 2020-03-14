<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

final class SendMessage extends TelegramMethods implements \JsonSerializable
{
    private int $chatId;
    private int $replyToMessageId;
    private string $text;

    public function __construct(int $chatId, int $replyToMessageId, string $text)
    {
        $this->chatId           = $chatId;
        $this->replyToMessageId = $replyToMessageId;
        $this->text             = $text;
    }

    public function jsonSerialize()
    {
        return [
            'chat_id'             => $this->chatId,
            'reply_to_message_id' => $this->replyToMessageId,
            'text'                => $this->text,
        ];
    }
}
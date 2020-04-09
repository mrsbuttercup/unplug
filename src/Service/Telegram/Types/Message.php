<?php

declare(strict_types=1);

namespace App\Service\Telegram\Types;

final class Message extends TelegramTypes implements \JsonSerializable
{
    public int $messageId;
    public User $from;
    public \DateTimeInterface $date;
    public Chat $chat;
    public ?string $text = null;

    public function jsonSerialize()
    {
        return [
            'message_id' => $this->messageId,
            'date'       => $this->date,
            'from'       => $this->from,
            'chat'       => $this->chat,
            'text'       => $this->text,
        ];
    }
}
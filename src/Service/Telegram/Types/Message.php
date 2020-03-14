<?php

declare(strict_types=1);

namespace App\Service\Telegram\Types;

final class Message extends TelegramTypes
{
    public int $messageId;
    public User $from;
    public \DateTimeInterface $date;
    public Chat $chat;
    public string $text;
}
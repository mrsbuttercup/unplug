<?php

declare(strict_types=1);

namespace App\Service\Telegram\Types;

final class Chat extends TelegramTypes
{
    public int $id;
    public string $title;
    public string $type; // “private”, “group”, “supergroup” or “channel”
}
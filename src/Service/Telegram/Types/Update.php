<?php

declare(strict_types=1);

namespace App\Service\Telegram\Types;

final class Update extends TelegramTypes
{
    public int $updateId;
    public Message $message;
    public Message $editedMessage;
}
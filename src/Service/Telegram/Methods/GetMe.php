<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

final class GetMe extends TelegramMethods implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [];
    }
}
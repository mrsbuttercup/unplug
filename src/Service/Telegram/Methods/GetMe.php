<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

final class GetMe extends TelegramMethods implements \JsonSerializable
{
    private const METHOD = 'GET';

    public function getMethod(): string
    {
        return self::METHOD;
    }

    public function jsonSerialize()
    {
        return [];
    }
}
<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

abstract class TelegramMethods
{
    public function getUri(): string
    {
        $reflectionClass = new \ReflectionClass($this);

        return lcfirst($reflectionClass->getShortName());
    }
}
<?php

declare(strict_types=1);

namespace App\Service\Telegram\Types;

final class WebhookInfo extends TelegramTypes
{
    public string $url;
    public array $allowedUpdates;
}
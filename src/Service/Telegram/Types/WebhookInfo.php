<?php

declare(strict_types=1);

namespace App\Service\Telegram\Types;

final class WebhookInfo extends TelegramTypes
{
    public string $url;
    public bool $hasCustomCertificate;
    public int $pendingUpdateCount;
    public ?int $lastErrorDate;
    public ?string $lastErrorMessage;
    public ?int $maxConnections;
    public array $allowedUpdates;
}
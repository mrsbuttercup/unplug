<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

/**
 * @see https://core.telegram.org/bots/api#setwebhook
 */
final class SetWebhook extends TelegramMethods implements \JsonSerializable
{
    private const ALLOWED_UPDATES = [
        'message',
        'edited_message',
        'edited_channel_post',
        'channel_post'
    ];

    private string $url;
    private array $allowedUpdates;

    public function __construct(string $url, array $allowedUpdates = [])
    {
        $this->url            = $url;
        $this->allowedUpdates = empty($allowedUpdates) ? self::ALLOWED_UPDATES : $allowedUpdates;
    }

    public function jsonSerialize()
    {
        return [
            'url'             => $this->url,
            'allowed_updates' => $this->allowedUpdates,
        ];
    }
}
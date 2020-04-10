<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

final class GetUpdates extends TelegramMethods implements \JsonSerializable
{
    private const METHOD = 'POST';

    private ?int $limit;
    private ?int $offset;

    public function __construct(?int $limit = null, ?int $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getMethod(): string
    {
        return self::METHOD;
    }

    public function jsonSerialize()
    {
        $json = [];

        if ($this->limit) {
            $json['limit'] = $this->limit;
        }

        if ($this->offset) {
            $json['offset'] = $this->offset;
        }

        return $json;
    }
}
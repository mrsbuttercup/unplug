<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use App\Service\Telegram\Methods\TelegramMethods;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class TelegramAPI
{
    private HttpClientInterface $httpClient;
    private string $telegramToken;

    public function __construct(HttpClientInterface $httpClient, string $telegramToken)
    {
        $this->httpClient    = $httpClient;
        $this->telegramToken = $telegramToken;
    }

    public function makeRequest(TelegramMethods $method): ResponseInterface
    {
        $body = \json_encode($method);

        return $this->httpClient->request(
            'POST',
            '/bot'.$this->telegramToken.'/'.$method->getUri(),
            [
                'headers' => [
                    'Content-Length' => strlen($body),
                ],
                'json'    => $body,
            ]
        );
    }
}
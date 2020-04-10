<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use App\Service\Telegram\Methods\TelegramMethods;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TelegramAPI
{
    private HttpClientInterface $httpClient;
    private string $baseUrl;

    public function __construct(HttpClientInterface $httpClient, string $baseUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl    = $baseUrl;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function makeRequest(TelegramMethods $method)
    {
        $body = \json_encode($method);

        $response = $this->httpClient->request(
            $method->getMethod(),
            $this->baseUrl . '/' . $method->getUri(),
            [
                'headers' => [
                    'Content-Length' => strlen($body),
                    'Content-Type'   => 'application/json',
                ],
                'body'    => $body,
            ]
        );

        if ($response->getStatusCode() === 200) {
            return \json_decode($response->getContent(), true);
        }

        throw new \DomainException($response->getContent());
    }
}
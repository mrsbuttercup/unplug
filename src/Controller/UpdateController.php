<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Telegram\Bot;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class UpdateController
{
    private Bot $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function __invoke(Request $request): ResponseInterface
    {
        $input = json_decode($request->getContent(), true);

        $this->bot->answer($input);
    }
}
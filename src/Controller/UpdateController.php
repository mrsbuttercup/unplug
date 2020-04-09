<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Telegram\Bot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class UpdateController extends AbstractController
{
    private Bot $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $input = \json_decode($request->getContent(), true);

        $message = $this->bot->sendAnswer($input);

        return $this->json(['ok' => true, 'result' => $message]);
    }
}
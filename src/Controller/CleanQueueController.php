<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Telegram\Bot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CleanQueueController extends AbstractController
{
    private Bot $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function __invoke()
    {
        $this->bot->cleanPendingUpdates();

        return $this->redirectToRoute('homepage');
    }
}
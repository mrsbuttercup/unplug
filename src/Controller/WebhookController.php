<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\WebhookType;
use App\Service\Telegram\Bot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class WebhookController extends AbstractController
{
    private Bot $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function __invoke(Request $request)
    {
        $form = $this->createForm(WebhookType::class, [], [
            'method' => 'get'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->bot->setWebhook($data['url']);
        }

        $me          = $this->bot->getMe();
        $webhookInfo = $this->bot->getWebhookInfo();

        return $this->render('webhook.html.twig', [
            'me'      => $me,
            'webhook' => $webhookInfo,
            'form'    => $form->createView(),
        ]);
    }
}
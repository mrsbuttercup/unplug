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
            $url  = null;

            try {
                $webhookInfo = $this->bot->setWebhook($data['url']);
                $url         = $webhookInfo->url;
            } catch (\Throwable $throwable) {

            }

        } else {
            try {
                $webhookInfo = $this->bot->getWebhookInfo();
                $url         = $webhookInfo->url;
            } catch (\Throwable $throwable) {
                $url = $this->generateUrl('update', [], UrlGeneratorInterface::ABSOLUTE_URL);;
            }
        }

        return $this->render('Webhook/index.html.twig', [
            'url'  => $url,
            'form' => $form->createView(),
        ]);
    }
}
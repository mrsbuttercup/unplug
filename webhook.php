<?php

require_once 'vendor/autoload.php';

use React\EventLoop\Factory;
use unreal4u\TelegramAPI\{
    HttpClientRequestHandler,
    Telegram\Methods\SetWebhook,
    TgLog
};
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$setWebhook      = new SetWebhook;
$setWebhook->url = $_ENV['WEBHOOK_URL'];
$setWebhook->allowed_updates = array(
    'message',
    'edited_message',
    'edited_channel_post',
    'channel_post'
);

$loop    = Factory::create();
$handler = new HttpClientRequestHandler($loop);
$tgLog   = new TgLog($_ENV['TELEGRAM_API_KEY'], $handler);

$tgLog->performApiRequest($setWebhook)
    ->then(function($response) {
        $result = $response->data;
        echo sprintf('Webhook result %s', $result);
    }, function($response) {
        $result = $response->data;
        echo sprintf('Webhook result %s', $result);
    });

$loop->run();

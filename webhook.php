<?php

require_once 'vendor/autoload.php';

use React\EventLoop\Factory;
use TelegramBot\ParametersBag;
use unreal4u\TelegramAPI\{
    HttpClientRequestHandler,
    Telegram\Methods\SetWebhook,
    TgLog
};

$setWebhook      = new SetWebhook;
$setWebhook->url = (new ParametersBag)->get('webhook_url');
$setWebhook->allowed_updates = array(
    'message',
    'edited_message',
    'edited_channel_post',
    'channel_post'
);

$apiKey  = (new ParametersBag)->get('telegram_api_key');
$loop    = Factory::create();
$handler = new HttpClientRequestHandler($loop);
$tgLog   = new TgLog($apiKey, $handler);

$tgLog->performApiRequest($setWebhook)
    ->then(function($response) {
        $result = $response->data;
        echo sprintf('Webhook result %s', $result);
    }, function($response) {
        $result = $response->data;
        echo sprintf('Webhook result %s', $result);
    });

$loop->run();

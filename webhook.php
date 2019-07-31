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

$loop    = Factory::create();
$handler = new HttpClientRequestHandler($loop);
$tgLog   = new TgLog($this->apiKey, $handler);

$tgLog->performApiRequest($setWebhook)
    ->then(function($response) {
        echo 'Webhook successful set';
        var_dump($response);
    }, function($response) {
        echo 'There was an error';
        var_dump($response);
    });

$loop->run();

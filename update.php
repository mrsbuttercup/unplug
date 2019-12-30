<?php

require_once 'vendor/autoload.php';

use TelegramBot\Bot;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

/**
 * We receive a JSON-serialized Update {@see https://core.telegram.org/bots/api#update}
 *
 * @see https://github.com/unreal4u/telegram-api/blob/master/src/Telegram/Types/Update.php
 * @see https://core.telegram.org/bots/api#getting-updates
 * @see https://core.telegram.org/bots/api#update
 */
$update = file_get_contents('php://input');
$input  = json_decode($update, true);

try {
    $bot = new Bot($_ENV['TELEGRAM_API_KEY']);
    $bot->loadInput($input)
        ->sendAnswer();
} catch (\Throwable $e) {
    // Log maybe?
}
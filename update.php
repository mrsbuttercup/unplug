<?php

require_once 'vendor/autoload.php';

use TelegramBot\Bot;

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
    $bot = new Bot($input);
    $bot->sendAnswer();
} catch (\Throwable $e) {
    // Log maybe?
}
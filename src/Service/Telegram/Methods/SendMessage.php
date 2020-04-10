<?php

declare(strict_types=1);

namespace App\Service\Telegram\Methods;

/**
 * @see https://core.telegram.org/bots/api#sendmessage
 */
final class SendMessage extends TelegramMethods implements \JsonSerializable
{
    private const METHOD     = 'POST';
    private const PARSE_MODE = 'HTML';

    private int $chatId;
    private int $replyToMessageId;
    private string $text;

    public function __construct(int $chatId, int $replyToMessageId, string $text)
    {
        $this->chatId           = $chatId;
        $this->replyToMessageId = $replyToMessageId;
        $this->text             = $text;
    }

    public function getMethod(): string
    {
        return self::METHOD;
    }

    public function jsonSerialize()
    {
        return [
            'chat_id'             => $this->chatId,
            'reply_to_message_id' => $this->replyToMessageId,
            'text'                => $this->text,
            'parse_mode'          => self::PARSE_MODE,
        ];
    }
}
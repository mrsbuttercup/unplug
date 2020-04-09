<?php

declare(strict_types=1);

namespace App\Tests\src\Service\Telegram\Types;

use App\Service\Telegram\Types\Chat;
use App\Service\Telegram\Types\Message;
use App\Service\Telegram\Types\Update;
use App\Service\Telegram\Types\User;
use PHPUnit\Framework\TestCase;

final class UpdateTest extends TestCase
{
    private const JSON_PATH = __DIR__.'/../../../../http/Update';

    /** @test */
    public function it_should_hydrate_an_update(): void
    {
        $incomingMessage = \file_get_contents(self::JSON_PATH . '/valid_update.json');
        $json            = \json_decode($incomingMessage, true);
        $update          = new Update($json);

        $this->assertInstanceOf(Update::class, $update);
        $this->assertInstanceOf(Message::class, $update->message);
        $this->assertInstanceOf(User::class, $update->message->from);
        $this->assertInstanceOf(Chat::class, $update->message->chat);
        $this->assertInstanceOf(\DateTimeInterface::class, $update->message->date);
    }
}
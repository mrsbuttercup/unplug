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
    /** @test */
    public function it_should_hydrate_an_update(): void
    {
        $incomingMessage = file_get_contents(__DIR__.'/../../../../http/Update/incoming_message.json');
        $update          = new Update(json_decode($incomingMessage, true));

        $this->assertInstanceOf(Update::class, $update);
        $this->assertInstanceOf(Message::class, $update->message);
        $this->assertInstanceOf(User::class, $update->message->from);
        $this->assertInstanceOf(Chat::class, $update->message->chat);
        $this->assertInstanceOf(\DateTimeInterface::class, $update->message->date);
    }
}
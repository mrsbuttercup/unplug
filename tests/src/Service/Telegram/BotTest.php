<?php

declare(strict_types=1);

namespace App\Tests\src\Service\Telegram;

use App\Service\Telegram\Bot;
use App\Service\Telegram\Types\Message;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BotTest extends WebTestCase
{
    private Bot $bot;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->bot = $container->get(Bot::class);
    }

    /**
     * @dataProvider invalidMessageProvider
     * @test
     */
    public function it_should_return_null(array $incomingMessage): void
    {
        $response = $this->bot->sendAnswer($incomingMessage);

        $this->assertNull($response);
    }

    /**
     * @dataProvider validMessageProvider
     * @test
     */
    public function it_should_send_answer_for_valid_message(array $incomingMessage): void
    {
        $messageSent = $this->bot->sendAnswer($incomingMessage);

        $this->assertInstanceOf(Message::class, $messageSent);
    }

    public function validMessageProvider()
    {
        $files = [
            'valid_update.json',
            'valid_update_with_zero.json',
        ];

        return \array_map([$this, 'readFile'], $files);
    }

    public function invalidMessageProvider()
    {
        $files = [
            'invalid_update_with_lorem_ipsum.json',
            'invalid_update_with_extra_chars.json',
            'invalid_update_with_mixed_values.json',
        ];

        return \array_map([$this, 'readFile'], $files);
    }

    private function readFile($file): array
    {
        $path        = __DIR__.'/../../../http/Update/';
        $fileContent = \file_get_contents($path.$file);
        $json        = \json_decode($fileContent, true);

        return [$json];
    }
}
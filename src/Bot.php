<?php


namespace TelegramBot;

use unreal4u\TelegramAPI\{
    HttpClientRequestHandler,
    TgLog,
    Interfaces\TelegramMethodDefinitions
};
use unreal4u\TelegramAPI\Telegram\Methods\{
    GetChatMembersCount,
    SendMessage
};
use unreal4u\TelegramAPI\Telegram\Types\{
    Chat,
    Message,
    Update
};
use React\EventLoop\Factory;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Twig\{
    Loader\FilesystemLoader,
    Environment
};

/**
 * Class Bot
 *
 * @package TelegramBot
 */
final class Bot
{
    /**
     * @var Update
     */
    private $input;

    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var Message
     */
    private $message;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * Bot constructor.
     *
     * @param $input
     */
    public function __construct($input)
    {
        $this->input = $input = new Update($input);

        /** @var Message $message */
        $this->message = $input->edited_message ?? $input->message;

        /** @var Chat $chat */
        $this->chat = $message->chat;

        $this->apiKey = (new ParametersBag)->get('telegram_api_key');
    }

    /**
     * @return PromiseInterface|null
     */
    public function sendAnswer()
    {
        $isValid = $this->isValid();
        if (!$isValid) {
            return null;
        }

        $countMembers          = new GetChatMembersCount;
        $countMembers->chat_id = $this->chat->id;

        $loop    = Factory::create();
        $handler = new HttpClientRequestHandler($loop);
        $tgLog   = new TgLog($this->apiKey, $handler);

        $tgLog->performApiRequest($countMembers)
            ->then(function($response) {
                $totalMembers = $response->data;

                return $totalMembers;
            })
            ->then(function($totalMembers) {
                if (is_numeric($totalMembers)) {
                    $responseMessage = $this->getTemplate(array(
                        'members'         => $totalMembers,
                        // 'members_on_line' => '<undefined>',
                        'members_on_line' => mt_rand($totalMembers, $totalMembers * time()),
                    ));

                    $sendMessage                      = new SendMessage;
                    $sendMessage->chat_id             = $this->chat->id;
                    $sendMessage->reply_to_message_id = $this->message->message_id;
                    $sendMessage->text                = $responseMessage;

                    return $sendMessage;
                }
            })
            ->then(function (SendMessage $sendMessage) use ($tgLog) {
                // Send answer to the gang
                return $tgLog->performApiRequest($sendMessage)
                    ->then(function($response) {
                        /** @var Message $answer */
                        $answer = $response->data;

                        return $answer;
                    });
            });

        $loop->run();
    }

    /**
     * @param array $templateVars
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getTemplate(array $templateVars = array()): string
    {
        $loader = new FilesystemLoader('Resources/views');
        $twig   = new Environment($loader, array(
            'cache' => '../var/cache',
        ));

        return $twig->render('shaming-list.html.twig', $templateVars);
    }

    /**
     * @return bool
     */
    private function isValid(): bool
    {
        if (!in_array($this->chat->type, array('group', 'supergroup'), true)) {
            return false;
        }

        $result = preg_match('/^[1-9]\d* mensaje+s{0,1}/', $this->message->text, $matches);
        if (!$result || !isset($matches) || empty($matches) || !count($matches)) {
            return false;
        }

        return true;
    }
}
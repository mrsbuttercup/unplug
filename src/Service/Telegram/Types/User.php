<?php

declare(strict_types=1);

namespace App\Service\Telegram\Types;

final class User extends TelegramTypes
{
    public int $id;
    public bool $isBot;
    public string $firstName;
    public string $lastName;
    public string $username;
    public bool $canJoinGroups;
    public bool $canReadAllGroupMessages;
}
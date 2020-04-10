<?php

declare(strict_types=1);

namespace App\Service\Telegram\Types;

use Doctrine\Common\Inflector\Inflector;

abstract class TelegramTypes
{
    private const TYPES = [
        'message'        => Message::class,
        'edited_message' => Message::class,
        'from'           => User::class,
        'chat'           => Chat::class,
    ];

    public function __construct(array $data = null)
    {
        $this->populateObject($data);
    }

    protected function populateObject(array $data)
    {
        $class = new \ReflectionClass($this);

        foreach ($data as $field => $value) {
            if (isset(self::TYPES[$field]) && is_array($value)) {
                $targetClass = self::TYPES[$field];

                $value = new $targetClass($value);
            }

            $field = Inflector::camelize($field);
            if ($class->hasProperty($field)) {
                $property = $class->getProperty($field);
                if ($property->getType()->getName() === \DateTimeInterface::class) {
                    $value = (new \DateTime())->setTimestamp($value);
                }

                $property->setValue($this, $value);
            }
        }
    }
}
<?php

namespace TelegramBot;

use Symfony\Component\Yaml\Yaml;

/**
 * Class ParametersBag
 *
 * @package TelegramBot
 */
final class ParametersBag
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * ParametersLoader constructor.
     */
    public function __construct()
    {
        $configFile       = file_get_contents('app/config/parameters.yml');
        $this->parameters = Yaml::parse($configFile);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (!isset($this->parameters[$key])) {
            @trigger_error(sprintf('Parameter %s does not exist', $key));
        }

        return $this->parameters[$key];
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        return isset($this->parameters[$key]);
    }
}
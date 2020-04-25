<?php

namespace kosuha606\EnvironmentModel;

/**
 * Class EnvironmentModelManager
 * @package kosuha606\EnvironmentModel
 */
class EnvironmentModelManager
{
    /** @var EnvironmentModelProvider */
    private $provider;

    private static $instance;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return EnvironmentModelProvider
     */
    public function getProvider(): EnvironmentModelProvider
    {
        return $this->provider;
    }

    /**
     * @param EnvironmentModelProvider $provider
     */
    public function setProvider(EnvironmentModelProvider $provider)
    {
        $this->provider = $provider;
    }
}
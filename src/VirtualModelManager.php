<?php

namespace kosuha606\EnvironmentModel;

/**
 * Class EnvironmentModelManager
 * @package kosuha606\EnvironmentModel
 */
class VirtualModelManager
{
    /** @var VirtualModelProvider */
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
     * @return VirtualModelProvider
     * @throws \Exception
     */
    public function getProvider($type=VirtualModelProvider::DEFAULT_PROVIDER_TYPE): VirtualModelProvider
    {
        if (!isset($this->provider[$type])) {
            throw new \Exception("There is now provider with type $type");
        }

        return $this->provider[$type];
    }

    /**
     * @param VirtualModelProvider $provider
     */
    public function setProvider(VirtualModelProvider $provider)
    {
        $this->provider[$provider->type()] = $provider;
    }
}
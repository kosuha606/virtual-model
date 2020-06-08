<?php

namespace kosuha606\VirtualModel;

/**
 * Class EnvironmentModelManager
 * @package kosuha606\VirtualModel
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
     * @return VirtualModelManager
     * @throws \Exception
     */
    public function setProvider(VirtualModelProvider $provider)
    {
        $providerClass = $provider->type();

        if (interface_exists($providerClass)) {
            if (!$provider instanceof $providerClass) {
                $realProviderClass = get_class($provider);
                throw new \Exception("Provider $realProviderClass must implement $providerClass");
            }
        }

        $this->provider[$provider->type()] = $provider;

        return $this;
    }
}
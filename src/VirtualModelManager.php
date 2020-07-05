<?php

namespace kosuha606\VirtualModel;

/**
 * Class EnvironmentModelManager
 * @package kosuha606\VirtualModel
 */
class VirtualModelManager
{
    /** @var VirtualModelProvider[] */
    private $providers;

    /** @var VirtualModelEntity[] */
    private static $entities;

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
        if (!isset($this->providers[$type])) {
            throw new \Exception("There is now provider with type $type");
        }

        return $this->providers[$type];
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

        $this->providers[$provider->type()] = $provider;

        return $this;
    }

    public static function getEntity($class)
    {
        if (isset(static::$entities[$class])) {
            return static::$entities[$class];
        }

        return $class;
    }

    public static function setEntities($entities)
    {
        static::$entities = $entities;
    }
}
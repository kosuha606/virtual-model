<?php

namespace kosuha606\VirtualModel;

use Exception;

class VirtualModelManager
{
    /** @var VirtualModelProvider[] */
    private array $providers;
    /** @var VirtualModelEntity[] */
    private static array $entities;
    private static ?VirtualModelManager $instance = null;

    /**
     * @return static
     */
    public static function getInstance(): VirtualModelManager
    {
        if (!self::$instance) {
            $className = static::class;
            self::$instance = new $className();
        }

        return self::$instance;
    }

    /**
     * @param string $type
     * @return VirtualModelProvider
     * @throws Exception
     */
    public function getProvider(string $type = VirtualModelProvider::DEFAULT_PROVIDER_TYPE): VirtualModelProvider
    {
        if (!isset($this->providers[$type])) {
            throw new Exception("There is now provider with type $type");
        }

        return $this->providers[$type];
    }

    /**
     * @param VirtualModelProvider $provider
     * @return VirtualModelManager
     * @throws Exception
     */
    public function setProvider(VirtualModelProvider $provider): VirtualModelManager
    {
        $providerClass = $provider->do('type');

        if (interface_exists($providerClass)) {
            if (!$provider instanceof $providerClass) {
                $realProviderClass = get_class($provider);
                throw new Exception("Provider $realProviderClass must implement $providerClass");
            }
        }

        $this->providers[$provider->do('type')] = $provider;

        return $this;
    }

    /**
     * @param string $class
     * @return VirtualModelEntity
     */
    public static function getEntity(string $class): VirtualModelEntity
    {
        if (isset(static::$entities[$class])) {
            return new static::$entities[$class];
        }

        return new $class;
    }

    public static function setEntities($entities)
    {
        static::$entities = $entities;
    }
}

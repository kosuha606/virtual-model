<?php

namespace kosuha606\VirtualModel;

use Exception;

/**
 * @property int $id
 * @method static self count($config)
 */
abstract class VirtualModelEntity
{
    public bool $isNewRecord = true;
    protected string $environment = 'db';
    protected array $attributes = [];

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public static function providerType(): string
    {
        return VirtualModelProvider::DEFAULT_PROVIDER_TYPE;
    }

    /**
     * @param string $environment
     */
    public function environment(string $environment): void
    {
        $this->environment = $environment;
    }

    /**
     * @param array $config
     * @return static
     * @throws Exception
     */
    public static function one($config = []): VirtualModelEntity
    {
        return VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->do('one', [static::class, $config]);
    }

    /**
     * @param array $config
     * @param null $indexBy
     * @return array
     * @throws Exception
     */
    public static function many(array $config = [], $indexBy = null): array
    {
        return VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->do('many', [static::class, $config, $indexBy]);
    }

    /**
     * @param array $attributes
     * @return static
     * @throws Exception
     */
    public static function create($attributes = []): VirtualModelEntity
    {
        return VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->do('buildModel', [
                    static::class,
                    $attributes
                ]
            );
    }

    /**
     * @param array $setAttributes
     * @return array
     * @throws Exception
     */
    public static function createMany($setAttributes = []): array
    {
        $result = [];

        foreach ($setAttributes as $attributes) {
            $result[] = self::create($attributes);
        }

        return $result;
    }

    /**
     * @param string $environment
     * @throws Exception
     */
    public function __construct($environment = 'db')
    {
        $this->environment = $environment;
        $this->initAttributes();
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void
    {
        if (!$attributes) {
            return;
        }

        foreach ($attributes as $attribute => $value) {
            $this->attributes[$attribute] = $value;
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @throws Exception
     */
    public function setAttribute(string $name, string $value): void
    {
        $this->ensureAttributeExists($name);
        $this->attributes[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return key_exists($name, $this->attributes);
    }

    /**
     * @throws Exception
     */
    public function load()
    {
        $loadMethod = $this->normalizeEnvMethod('load');

        if (method_exists($this, $loadMethod)) {
            $this->$loadMethod();
        } else {
            foreach ($this->attributes as $attrName => $attrValue) {
                $this->setAttribute($attrName, $this->$attrName);
            }
        }
    }

    /**
     * @param array $config
     * @return null
     * @throws Exception
     */
    public function save(array $config = [])
    {
        $saveMethod = $this->normalizeEnvMethod('save');

        if (method_exists($this, $saveMethod)) {
            return $this->$saveMethod($config);
        }

        VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->do('persist', [$this]);

        return VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->do('flush');
    }

    /**
     * @throws Exception
     */
    public function delete()
    {
        VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->do('delete', [$this]);
    }

    /**
     * @param string $snakeName
     * @return mixed
     * @throws Exception
     */
    public function __get(string $snakeName)
    {
        $this->ensureAttributeExists($snakeName);
        $getterName = $this->normalizeEnvMethod('get_' . $snakeName);

        if (method_exists($this, $getterName)) {
            return $this->$getterName();
        }

        $commonGetterName = $this->normalizeCommonMethod('get_' . $snakeName);
        if (method_exists($this, $commonGetterName)) {
            return $this->$commonGetterName();
        }

        if (property_exists($this, $snakeName)) {
            return $this->$snakeName;
        }

        return $this->attributes[$snakeName];
    }

    /**
     * @param string $snakeName
     * @param mixed $value
     * @return mixed|null
     * @throws Exception
     */
    public function __set(string $snakeName, $value)
    {
        $this->ensureAttributeExists($snakeName);
        $setterName = $this->normalizeEnvMethod('set_' . $snakeName);

        if (method_exists($this, $setterName)) {
            return $this->$setterName($value);
        }

        $commonSetterName = $this->normalizeCommonMethod('set_' . $snakeName);
        if (method_exists($this, $commonSetterName)) {
            return $this->$commonSetterName($value);
        }

        if ($this->hasAttribute($snakeName)) {
            $this->setAttribute($snakeName, $value);
        }

        if (property_exists($this, $snakeName)) {
            $this->$snakeName = $value;
        }

        return null;
    }

    /**
     * @param string $name
     * @throws Exception
     */
    protected function ensureAttributeExists(string $name): void
    {
        if (
            !key_exists($name, $this->attributes) &&
            !property_exists($this, $name)
        ) {
            $envModelName = get_class($this);
            throw new Exception("No such attribute $name in env model named $envModelName");
        }
    }

    /**
     * @param string $snakeString
     * @return mixed|string
     */
    public function normalizeEnvMethod(string $snakeString): string
    {
        return StringHelper::normalizeEnvMethod($snakeString, $this->environment);
    }

    /**
     * @param string $snakeString
     * @return mixed|string
     */
    public function normalizeCommonMethod(string $snakeString): string
    {
        return StringHelper::snakeToCamel($snakeString);
    }

    /**
     * @throws Exception
     */
    protected function initAttributes()
    {
        foreach ($this->attributes() as $attribute) {
            $this->attributes[$attribute] = null;
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $props = get_object_vars($this);
        $result = array_merge($props, $this->getAttributes());
        unset($result['attributes']);

        return $result;
    }

    /**
     * @param array $items
     * @return array
     */
    public static function allToArray(array $items): array
    {
        return array_map(static function ($item) {
            /** @var VirtualModelEntity $item */
            return $item->toArray();
        }, $items);
    }

    /**
     * @param string $name
     * @param array $inputArgs
     * @return mixed|null
     * @throws Exception
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function __call(string $name, array $inputArgs = [])
    {
        return static::translateToProvider($name, $inputArgs);
    }

    /**
     * @param string $name
     * @param array $inputArgs
     * @return mixed|null
     * @throws Exception
     * @noinspection PhpMissingReturnTypeInspection
     */
    public static function __callStatic(string $name, array $inputArgs = [])
    {
        return static::translateToProvider($name, $inputArgs);
    }

    /**
     * @param string $name
     * @param array $inputArgs
     * @return false|mixed
     * @throws Exception
     * @noinspection PhpMissingReturnTypeInspection
     */
    protected static function translateToProvider(string $name, array $inputArgs = [])
    {
        $result = null;
        $arguments = [static::class];
        $arguments = array_merge($arguments, $inputArgs);

        if (VirtualModelManager::getInstance()->getProvider(static::providerType())->has($name)) {
            $result = VirtualModelManager::getInstance()->getProvider(static::providerType())->do($name, $arguments);
        } else {
            throw new Exception("No such method $name in related provider");
        }

        return $result;
    }
}

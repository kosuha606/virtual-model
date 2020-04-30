<?php

namespace kosuha606\VirtualModel;

/**
 * Одна абстрактная модель
 * @package kosuha606\VirtualModel
 */
abstract class VirtualModel
{
    public $isNewRecord = true;

    /**
     * @var string
     */
    protected $environment = 'db';

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @description Обязывает установить ключи атрибутов
     * @return array
     */
    abstract public function attributes(): array;

    /**
     * @return string
     */
    public static function providerType()
    {
        return VirtualModelProvider::DEFAULT_PROVIDER_TYPE;
    }

    /**
     * @description Установить окружение в слой моделей
     * @param $environent
     */
    public function environment($environent)
    {
        $this->environment = $environent;
    }

    /**
     * @param array $config
     * @return static
     * @throws \Exception
     */
    public static function one($config = [])
    {
        return VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->one(static::class, $config)
        ;
    }

    /**
     * @param array $config
     * @return array
     * @throws \Exception
     */
    public static function many($config = [])
    {
        return VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->many(static::class, $config)
        ;
    }

    /**
     * @return static
     * @throws \Exception
     */
    public static function create($attributes = [])
    {
        $instance = VirtualModelManager::getInstance()->getProvider(static::providerType())->buildModel(
            static::class,
            $attributes
        );

        return $instance;
    }

    /**
     * @description Конструктор сначала инициализирует атрибуты пустыми
     * @description значениями, а потом заполняет атрибуты данными для окружения
     * @throws \Exception
     */
    public function __construct($environment = 'db')
    {
        $this->environment = $environment;
        $this->initAttributes();
        // $this->load();
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function setAttribute($name, $value)
    {
        $this->ensureAttributeExists($name);
        $this->attributes[$name] = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @description Загрузить данные в атрибуты
     * @throws \Exception
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
     * @description Сохранить модель
     * @throws \Exception
     */
    public function save($config = [])
    {
        $saveMethod = $this->normalizeEnvMethod('save');

        if (method_exists($this, $saveMethod)) {
            return $this->$saveMethod($config);
        }

        VirtualModelManager::getInstance()->getProvider(static::providerType())->persist($this);
        VirtualModelManager::getInstance()->getProvider(static::providerType())->flush();

        return null;
    }

    /**
     * @throws \Exception
     */
    public function delete()
    {
        VirtualModelManager::getInstance()->getProvider(static::providerType())->delete($this);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($snakeName)
    {
        $this->ensureAttributeExists($snakeName);
        $getterName = $this->normalizeEnvMethod('get_'.$snakeName);

        if (method_exists($this, $getterName)) {
            return $this->$getterName();
        }

        $commonGetterName = $this->normalizeCommonMethod('get_'.$snakeName);
        if (method_exists($this, $commonGetterName)) {
            return $this->$commonGetterName();
        }

        if (property_exists($this, $snakeName)) {
            return $this->$snakeName;
        }

        return $this->attributes[$snakeName];
    }

    /**
     * @param $name
     * @param $value
     * @return mixed|null
     * @throws \Exception
     */
    public function __set($snakeName, $value)
    {
        $this->ensureAttributeExists($snakeName);
        $setterName = $this->normalizeEnvMethod('set_'.$snakeName);

        if (method_exists($this, $setterName)) {
            return $this->$setterName($value);
        }

        $commonSetterName = $this->normalizeCommonMethod('set_'.$snakeName);
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
     * @param $name
     * @throws \Exception
     */
    protected function ensureAttributeExists($name)
    {
        if (
            !isset($this->attributes[$name]) &&
            !property_exists($this, $name)
        ) {
            $envModelName = get_class($this);
            throw new \Exception("No such attribute $name in env model named $envModelName");
        }
    }

    /**
     * @param $snakeString
     * @return mixed|string
     */
    public function normalizeEnvMethod($snakeString)
    {
        return StringHelper::normalizeEnvMethod($snakeString, $this->environment);
    }

    /**
     * @param $snakeString
     * @return mixed|string
     */
    public function normalizeCommonMethod($snakeString)
    {
        return StringHelper::snakeToCamel($snakeString);
    }

    /**
     * @throws \Exception
     */
    protected function initAttributes()
    {
        foreach ($this->attributes() as $attribute) {
            $this->attributes[$attribute] = null;
        }
    }

    public function toArray()
    {
        $props = get_object_vars($this);
        $result = array_merge($props, $this->getAttributes());
        unset($result['attributes']);

        return $result;
    }
}
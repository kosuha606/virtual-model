<?php

namespace kosuha606\VirtualModel;

/**
 * Одна абстрактная модель
 * @package kosuha606\VirtualModel
 */
abstract class VirtualModelEntity
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
    public function attributes(): array
    {
        return [];
    }

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
    public static function many($config = [], $indexBy = null)
    {
        return VirtualModelManager::getInstance()
            ->getProvider(static::providerType())
            ->many(static::class, $config, $indexBy)
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
     * @param array $setAttributes
     * @return array
     * @throws \Exception
     */
    public static function createMany($setAttributes = [])
    {
        $result = [];

        foreach ($setAttributes as $attributes) {
            $result[] = self::create($attributes);
        }

        return $result;
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
        if (!$attributes) {
            return;
        }

        foreach ($attributes as $attribute => $value) {
            $this->attributes[$attribute] = $value;
        }
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
        return key_exists($name, $this->attributes);
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

        return VirtualModelManager::getInstance()->getProvider(static::providerType())->flush();
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
            !key_exists($name, $this->attributes) &&
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

    /**
     * Перевести массив объектов в обычный массив
     * @param $item
     * @return array
     */
    public static function allToArray($items)
    {
        return array_map(function($item) {
            /** @var VirtualModelEntity $item */
            return $item->toArray();
        }, $items);
    }

    /**
     * Возможность обратиться к методу провайдера из контекста объекта
     * @TODO дублирование с _callStatic
     * @param $name
     * @param array $inputArgs
     * @return mixed|null
     * @throws \Exception
     */
    public function __call($name, $inputArgs = [])
    {
        $result = null;
        $arguments = [static::class];
        $arguments = array_merge($arguments, $inputArgs);

        if (method_exists(VirtualModelManager::getInstance()->getProvider(static::providerType()), $name)) {
            $result = call_user_func_array([
                VirtualModelManager::getInstance()->getProvider(static::providerType()),
                $name
            ], $arguments);
        } else {
            throw new \Exception("No such method $name in related provider");
        }

        return $result;
    }

    /**
     * Позволяет вызывать метод провайдера через модель
     * @param $name
     * @param $arguments
     * @return mixed|null
     * @throws \Exception
     */
    public static function __callStatic($name, $inputArgs = [])
    {
        $result = null;
        $arguments = [static::class];
        $arguments = array_merge($arguments, $inputArgs);

        if (method_exists(VirtualModelManager::getInstance()->getProvider(static::providerType()), $name)) {
            $result = call_user_func_array([
                VirtualModelManager::getInstance()->getProvider(static::providerType()),
                $name
            ], $arguments);
        } else {
            throw new \Exception("No such method $name in related provider");
        }

        return $result;
    }
}
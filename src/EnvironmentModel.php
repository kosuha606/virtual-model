<?php

namespace kosuha606\EnvironmentModel;

/**
 * Одна абстрактная модель
 * @package kosuha606\EnvironmentModel
 */
abstract class EnvironmentModel
{
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
     * @description Установить окружение в слой моделей
     * @param $environent
     */
    public function environment($environent)
    {
        $this->environment = $environent;
    }

    /**
     * @return EnvironmentModel
     * @throws \Exception
     */
    public static function create()
    {
        $instance = new static();

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
     */
    public function save($config)
    {
        $saveMethod = $this->normalizeEnvMethod('save');

        if (method_exists($this, $saveMethod)) {
            return $this->$saveMethod($config);
        }

        return null;
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

        return null;
    }

    /**
     * @param $name
     * @throws \Exception
     */
    protected function ensureAttributeExists($name)
    {
        if (!isset($this->attributes[$name])) {
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
}
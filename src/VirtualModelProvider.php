<?php

namespace kosuha606\EnvironmentModel;

/**
 * @description Провайдер для работы с сущностями модели
 * @package kosuha606\EnvironmentModel
 */
abstract class VirtualModelProvider
{
    /**
     * Тип провайдера по умолчанию
     */
    const DEFAULT_PROVIDER_TYPE = 'storage';

    /**
     * @return string
     */
    abstract public function environemnt(): string;

    /**
     * @var array
     */
    protected $persistedModels = [];

    /**
     * @param $modelClass
     * @param array $attributes
     * @return VirtualModel
     */
    public function buildModel($modelClass, $attributes = [])
    {
        /** @var VirtualModel $instance */
        $instance = new $modelClass($this->environemnt());
        $instance->setAttributes($attributes);

        return $instance;
    }

    /**
     * @param $modelClass
     * @param $config
     * @return VirtualModel
     */
    public function one($modelClass, $config)
    {
        $attributes = $this->findOne($modelClass, $config);
        $model = $this->buildModel($modelClass, $attributes);
        $model->isNewRecord = false;

        return $model;
    }

    /**
     * @param $modelClass
     * @param $config
     * @return mixed
     */
    abstract protected function findOne($modelClass, $config);

    /**
     * @param $modelClass
     * @param $config
     * @return array
     * @throws \Exception
     */
    public function many($modelClass, $config, $indexBy = null)
    {
        $attributesArray = $this->findMany($modelClass, $config);
        $result = [];

        foreach ($attributesArray as $attributes) {
            $model = $this->buildModel($modelClass, $attributes);
            $model->isNewRecord = false;

            if ($indexBy) {
                if (!isset($attributes[$indexBy])) {
                    throw new \Exception("No such attribute $indexBy for index in provider");
                }
                $result[$attributes[$indexBy]] = $model;
            } else {
                $result[] = $model;
            }
        }

        return $result;
    }

    /**
     * @param $modelClass
     * @param $config
     * @return mixed
     */
    abstract protected function findMany($modelClass, $config);

    public function persist(VirtualModel $model)
    {
        $this->persistedModels[] = $model;
    }

    public function flush()
    {
        $this->persistedModels = [];
    }

    public function delete(VirtualModel $model)
    {
        // Delete model
    }

    /**
     * @return string
     */
    public function type()
    {
        return self::DEFAULT_PROVIDER_TYPE;
    }
}
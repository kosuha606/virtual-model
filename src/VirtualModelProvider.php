<?php

namespace kosuha606\VirtualModel;

/**
 * @description Провайдер для работы с сущностями модели
 * @package kosuha606\VirtualModel
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
    public function environemnt()
    {
        return 'storage';
    }

    /**
     * @var array
     */
    protected $persistedModels = [];

    /**
     * @param $modelClass
     * @param array $attributes
     * @return VirtualModelEntity
     */
    public function buildModel($modelClass, $attributes = [])
    {
        /** @var VirtualModelEntity $instance */
        $instance = new $modelClass($this->environemnt());
        $instance->setAttributes($attributes);

        return $instance;
    }

    /**
     * @param $modelClass
     * @param $config
     * @return VirtualModelEntity
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
    protected function findOne($modelClass, $config)
    {
        return [];
    }

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
    protected function findMany($modelClass, $config)
    {
        return [];
    }

    public function persist(VirtualModelEntity $model)
    {
        $this->persistedModels[] = $model;
    }

    public function flush()
    {
        $this->persistedModels = [];

        return null;
    }

    public function delete(VirtualModelEntity $model)
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

    /**
     * Провайдер может вернуть набор классов о которых ему известно
     * это может быть полезно для поддержки массовых операций над
     * одними и темиже типами моеделей
     *
     * @return array
     */
    public function getAvailableModelClasses()
    {
        return [];
    }
}
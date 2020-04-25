<?php

namespace kosuha606\EnvironmentModel;

/**
 * @description Провайдер для работы с сущностями модели
 * @package kosuha606\EnvironmentModel
 */
abstract class EnvironmentModelProvider
{
    /**
     * @return string
     */
    abstract public function environemnt(): string;

    /**
     * @param $modelClass
     * @param array $attributes
     * @return EnvironmentModel
     */
    public function buildModel($modelClass, $attributes = [])
    {
        /** @var EnvironmentModel $instance */
        $instance = new $modelClass($this->environemnt());
        $instance->setAttributes($attributes);

        return $instance;
    }

    /**
     * @param $modelClass
     * @param $config
     * @return EnvironmentModel
     */
    public function one($modelClass, $config)
    {
        $attributes = $this->findOne($modelClass, $config);

        return $this->buildModel($modelClass, $attributes);
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
            if ($indexBy) {
                if (!isset($attributes[$indexBy])) {
                    throw new \Exception("No such attribute $indexBy for index in provider");
                }
                $result[$attributes[$indexBy]] = $this->buildModel($modelClass, $attributes);
            } else {
                $result[] = $this->buildModel($modelClass, $attributes);
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
}
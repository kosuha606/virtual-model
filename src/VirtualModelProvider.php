<?php

namespace kosuha606\VirtualModel;

use Exception;
use LogicException;

/**
 * @package kosuha606\VirtualModel
 */
abstract class VirtualModelProvider
{
    public const DEFAULT_PROVIDER_TYPE = 'storage';

    private string $lastError;
    protected array $persistedModels = [];

    /**
     * @return string
     */
    public function environment(): string
    {
        return 'storage';
    }

    /**
     * @param string $modelClass
     * @param array $attributes
     * @return VirtualModelEntity
     */
    public function buildModel(string $modelClass, array $attributes = []): VirtualModelEntity
    {
        /** @var VirtualModelEntity $instance */
        $instance = new $modelClass($this->environment());
        $instance->setAttributes($attributes);

        return $instance;
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @return VirtualModelEntity
     */
    public function one(string $modelClass, array $config): VirtualModelEntity
    {
        $attributes = $this->findOne($modelClass, $config);
        $model = $this->buildModel($modelClass, $attributes);
        $model->isNewRecord = false;

        return $model;
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @return mixed
     */
    protected function findOne(string $modelClass, array $config): array
    {
        $this->lastError = $modelClass . ' ' . json_encode($config);

        return [];
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @param null $indexBy
     * @return array
     * @throws Exception
     */
    public function many(string $modelClass, array $config, $indexBy = null): array
    {
        $attributesArray = $this->findMany($modelClass, $config);
        $result = [];

        foreach ($attributesArray as $attributes) {
            $model = $this->buildModel($modelClass, $attributes);
            $model->isNewRecord = false;

            if ($indexBy) {
                if (!isset($attributes[$indexBy])) {
                    throw new Exception("No such attribute $indexBy for index in provider");
                }
                $result[$attributes[$indexBy]] = $model;
            } else {
                $result[] = $model;
            }
        }

        return $result;
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @return mixed
     */
    protected function findMany(string $modelClass, array $config): array
    {
        $this->lastError = $modelClass . ' ' . json_encode($config);

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

    /**
     * @param VirtualModelEntity $model
     */
    public function delete(VirtualModelEntity $model)
    {
        throw new LogicException('Not implemented' . get_class($model));
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return self::DEFAULT_PROVIDER_TYPE;
    }

    /**
     * @return array
     */
    public function getAvailableModelClasses(): array
    {
        return [];
    }
}

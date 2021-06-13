<?php

namespace kosuha606\VirtualModel;

use Exception;
use LogicException;

abstract class VirtualModelProvider
{
    public const DEFAULT_PROVIDER_TYPE = 'storage';

    use ActionMethodTrait;

    protected array $persistedModels = [];

    public function __construct()
    {
        $this->specifyActions([
            'environment' => static function (): string {
                return self::DEFAULT_PROVIDER_TYPE;
            },

            'buildModel' => static function (
                string $modelClass,
                array $attributes = []
            ): VirtualModelEntity {
                /** @var VirtualModelEntity $instance */
                $instance = new $modelClass($this->do('environment'));
                $instance->setAttributes($attributes);

                return $instance;
            },

            'one' => static function (
                string $modelClass,
                array $config
            ): VirtualModelEntity {
                $attributes = $this->do('findOne', [$modelClass, $config]);
                $model = $this->do('buildModel', [$modelClass, $attributes]);
                $model->isNewRecord = false;

                return $model;
            },

            'findOne' => static function (
                string $modelClass, array $config
            ): array {
                $this->lastError = $modelClass . ' ' . json_encode($config);

                return [];
            },

            'many' => static function (
                string $modelClass,
                array $config,
                $indexBy = null
            ): array {
                $attributesArray = $this->do('findMany', [$modelClass, $config]);
                $result = [];

                foreach ($attributesArray as $attributes) {
                    $model = $this->do('buildModel', [$modelClass, $attributes]);
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
            },

            'findMany' => static function (
                string $modelClass,
                array $config
            ): array {
                $this->lastError = $modelClass . ' ' . json_encode($config);

                return [];
            },

            'persist' => static function (VirtualModelEntity $model) {
                $this->persistedModels[] = $model;
            },

            'flush' => static function () {
                $this->persistedModels = [];

                return null;
            },

            'delete' => static function (VirtualModelEntity $model) {
                throw new LogicException('Not implemented' . get_class($model));
            },

            'type' => static function (): string {
                return self::DEFAULT_PROVIDER_TYPE;
            },

            'getAvailableModelClasses' => static function (): array {
                return [];
            }
        ]);
    }
}

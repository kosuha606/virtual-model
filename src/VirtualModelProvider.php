<?php

namespace kosuha606\VirtualModel;

use Exception;
use LogicException;

abstract class VirtualModelProvider
{
    public const DEFAULT_PROVIDER_TYPE = 'storage';

    protected array $persistedModels = [];
    protected array $actions = [];
    protected array $parentActions = [];
    protected string $lastError = '';

    public function __construct()
    {
        $this->specifyActions([
            'environment' => static function (): string {
                return self::DEFAULT_PROVIDER_TYPE;
            },

            'buildModel' => function (
                string $modelClass,
                array $attributes = []
            ): VirtualModelEntity {
                /** @var VirtualModelEntity $instance */
                $instance = new $modelClass($this->do('environment'));
                $instance->setAttributes($attributes);

                return $instance;
            },

            'one' => function (
                string $modelClass,
                array $config
            ): VirtualModelEntity {
                $attributes = $this->do('findOne', [$modelClass, $config]);
                $model = $this->do('buildModel', [$modelClass, $attributes]);
                $model->isNewRecord = false;

                return $model;
            },

            'findOne' => function (
                string $modelClass, array $config
            ): array {
                $this->lastError = $modelClass . ' ' . json_encode($config);

                return [];
            },

            'many' => function (
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

            'findMany' => function (
                string $modelClass,
                array $config
            ): array {
                $this->lastError = $modelClass . ' ' . json_encode($config);

                return [];
            },

            'persist' => function (VirtualModelEntity $model) {
                $this->persistedModels[] = $model;
            },

            'flush' => function () {
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

    /**
     * @param array $actions
     * @param bool $merge
     */
    public function specifyActions(array $actions, bool $merge = false): void
    {
        if ($merge) {
            $this->parentActions = $this->actions;

            foreach ($actions as $name => $function) {
                $this->actions[$name] = $function;
            }

            return;
        }

        $this->actions = $actions;
    }

    /**
     * @param string $action
     * @return bool
     */
    public function has(string $action): bool
    {
        return isset($this->actions[$action]);
    }

    /**
     * @param string $action
     * @param array $arguments
     * @param bool $exception
     * @return false|mixed
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function do(string $action, array $arguments = [], bool $exception = false)
    {
        $this->lastError = '';

        if (!$this->has($action)) {
            $className = get_class($this);
            $errorMessage = "Action $action is not defined $className";
            $this->lastError = $errorMessage;

            if ($exception) {
                throw new LogicException($errorMessage);
            }

            return false;
        }

        return call_user_func_array($this->actions[$action], $arguments);
    }

    /**
     * @param string $action
     * @param array $arguments
     * @return false
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function doParent(string $action, array $arguments)
    {
        if (!isset($this->parentActions[$action])) {
            return false;
        }

        return call_user_func_array($this->parentActions[$action], $arguments);
    }
}

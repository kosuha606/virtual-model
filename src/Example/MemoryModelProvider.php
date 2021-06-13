<?php

namespace kosuha606\VirtualModel\Example;

use Exception;
use kosuha606\VirtualModel\VirtualModelEntity;
use kosuha606\VirtualModel\VirtualModelProvider;

/**
 * Stores data in memory, useful for tests or
 * for representing data in objects in runtime
 */
class MemoryModelProvider extends VirtualModelProvider
{
    const MEM_ENVIRONMENT = 'mem';

    public array $memoryStorage = [];

    public function __construct()
    {
        parent::__construct();
        $this->specifyActions([
            'environment' => static function (): string {
                return self::MEM_ENVIRONMENT;
            },

            'findOne' => function (string $modelClass, array $config) {
                if (!$this->do('isCorrectConditions', [$modelClass, $config])) {
                    return null;
                }

                return $this->do('findInStorage', [$modelClass, $config, true]);
            },

            'findMany' => function (string $modelClass, array $config) {
                if (!$this->do('isCorrectConditions', [$modelClass, $config])) {
                    return [];
                }

                return $this->do('findInStorage', [$modelClass, $config, false]);
            },

            'isCorrectConditions' => function (string $modelClass, array $config) {
                if (!isset($this->memoryStorage[$modelClass])) {
                    return false;
                }

                if (!isset($config['where'])) {
                    return false;
                }

                return true;
            },

            'findInStorage' => function ($modelClass, $config, $isGreedy = true) {
                $result = [];

                // @TODO order implementation
                $limit = 9999;
                if (isset($config['limit'])) {
                    $limit = $config['limit'];
                }

                foreach ($this->memoryStorage[$modelClass] as $item) {
                    $isMatch = true;

                    foreach ($config['where'] as $whereConfig) {
                        switch ($whereConfig[0]) {
                            case 'all':
                                $isMatch = $isMatch && true;
                                break;
                            case 'in':
                                if (!in_array($item[$whereConfig[1]], $whereConfig[2])) {
                                    $isMatch = $isMatch && false;
                                }
                                break;
                            case '=':
                                if ($item[$whereConfig[1]] != $whereConfig[2]) {
                                    $isMatch = $isMatch && false;
                                }
                                break;
                        }
                    }

                    if ($isMatch) {
                        if ($isGreedy) {
                            $result = $item;
                            break;
                        } else {
                            $result[] = $item;
                        }
                    }

                    if (count($result) >= $limit) {
                        break;
                    }
                }

                return $result;
            },

            'flush' => function () {
                $result = [];

                /** @var VirtualModelEntity $model */
                foreach ($this->persistedModels as $model) {
                    $modelClass = get_class($model);

                    if ($model->isNewRecord) {
                        if ($model->hasAttribute('id')) {
                            if (!$model->id) {
                                $model->id = $this->do('getNextId', [$modelClass]);
                            }

                            $result = [$model->id];
                        }

                        $this->memoryStorage[$modelClass][] = $model->getAttributes();
                    } else {
                        if (!$model->hasAttribute('id')) {
                            throw new Exception("Model $modelClass has no id attribute and cant be saved");
                        }

                        foreach ($this->memoryStorage[$modelClass] as $key => $possibleModel) {
                            if ($possibleModel['id'] === $model->id) {
                                $this->memoryStorage[$modelClass][$key] = $model->getAttributes();
                                $result[] = $possibleModel['id'];
                            }
                        }
                    }
                }

                $this->persistedModels = [];

                return $result;
            },

            'delete' => function (VirtualModelEntity $model) {
                $modelClass = get_class($model);

                if (!$model->hasAttribute('id')) {
                    throw new Exception("Model $modelClass has no id attribute and cant be removed");
                }

                $result = false;

                foreach ($this->memoryStorage[$modelClass] as $key => $possibleModel) {
                    if ($possibleModel['id'] === $model->id) {
                        unset($this->memoryStorage[$modelClass][$key]);
                        $result = true;
                        break;
                    }
                }

                return $result;
            },

            'count' => function ($modelClass, $config) {
                if (!$this->do('isCorrectConditions', [$modelClass, $config])) {
                    return 0;
                }

                return count($this->do('findInStorage', [$modelClass, $config, false]));
            },

            'getNextId' => function ($modelClass) {
                if (!isset($this->memoryStorage[$modelClass])) {
                    return 0;
                }

                $items = $this->memoryStorage[$modelClass];

                $maxId = 0;
                foreach ($items as $item) {
                    if (
                        isset($item['id']) &&
                        $item['id'] >= $maxId
                    ) {
                        $maxId = $item['id'] + 1;
                    }
                }

                return $maxId;
            },
        ], true);
    }
}
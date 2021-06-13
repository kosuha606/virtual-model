<?php

namespace kosuha606\VirtualModel\Example;

use kosuha606\VirtualModel\VirtualModelEntity;
use kosuha606\VirtualModel\VirtualModelProvider;

/**
 * Провайдер для хранения данных в памяти программы
 * @package kosuha606\VirtualModel\example
 */
class MemoryModelProvider extends VirtualModelProvider
{
    /**
     * @var array
     */
    public $memoryStorage = [];

    /**
     * @return string
     */
    public function environment(): string
    {
        return 'mem';
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @return array|mixed|null
     */
    protected function findOne(string $modelClass, array $config)
    {
        if (!$this->isCorrectConditions($modelClass, $config)) {
            return null;
        }

        return $this->findInStorage($modelClass, $config, true);
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @return array|mixed
     */
    protected function findMany(string $modelClass, array $config)
    {
        if (!$this->isCorrectConditions($modelClass, $config)) {
            return [];
        }

        return $this->findInStorage($modelClass, $config, false);
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @return bool
     */
    private function isCorrectConditions($modelClass, $config)
    {
        if (!isset($this->memoryStorage[$modelClass])) {
            return false;
        }

        if (!isset($config['where'])) {
            return false;
        }

        return true;
    }

    /**
     * Поиск в памяти
     * @param string $modelClass
     * @param array $config
     * @param bool $isGreedy
     * @return array
     */
    private function findInStorage($modelClass, $config, $isGreedy = true)
    {
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
    }

    /**
     * @throws \Exception
     */
    public function flush()
    {
        $result = [];

        /** @var VirtualModelEntity $model */
        foreach ($this->persistedModels as $model) {
            $modelClass = get_class($model);

            if ($model->isNewRecord) {
                if ($model->hasAttribute('id')) {
                    if (!$model->id) {
                        $model->id = $this->getNextId($modelClass);
                    }

                    $result = [$model->id];
                }

                $this->memoryStorage[$modelClass][] = $model->getAttributes();
            } else {
                if (!$model->hasAttribute('id')) {
                    throw new \Exception("Model $modelClass has no id attribute and cant be saved");
                }

                foreach ($this->memoryStorage[$modelClass] as $key => $possibleModel) {
                    if ($possibleModel['id'] === $model->id) {
                        $this->memoryStorage[$modelClass][$key] = $model->getAttributes();
                        $result[] = $possibleModel['id'];
                    }
                }
            }
        }

        parent::flush();

        return $result;
    }

    /**
     * @param VirtualModelEntity $model
     * @return bool
     * @throws \Exception
     */
    public function delete(VirtualModelEntity $model)
    {
        $modelClass = get_class($model);

        if (!$model->hasAttribute('id')) {
            throw new \Exception("Model $modelClass has no id attribute and cant be removed");
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
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @return int
     */
    public function count($modelClass, $config)
    {
        if (!$this->isCorrectConditions($modelClass, $config)) {
            return 0;
        }

        return count($this->findInStorage($modelClass, $config, false));
    }

    /**
     * @param string $modelClass
     * @return int
     * @throws \Exception
     */
    public function getNextId($modelClass)
    {
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
                $maxId = $item['id']+1;
            }
        }

        return $maxId;
    }
}
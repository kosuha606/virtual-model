<?php

namespace kosuha606\EnvironmentModel\Example;

use kosuha606\EnvironmentModel\VirtualModel;
use kosuha606\EnvironmentModel\VirtualModelProvider;

/**
 * Провайдер для хранения данных в памяти программы
 * @package kosuha606\EnvironmentModel\example
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
    public function environemnt(): string
    {
        return 'mem';
    }

    /**
     * @param $modelClass
     * @param $config
     * @return array|mixed|null
     */
    protected function findOne($modelClass, $config)
    {
        if (!$this->isCorrectConditions($modelClass, $config)) {
            return null;
        }

        return $this->findInStorage($modelClass, $config, true);
    }

    /**
     * @param $modelClass
     * @param $config
     * @return array|mixed
     */
    protected function findMany($modelClass, $config)
    {
        if (!$this->isCorrectConditions($modelClass, $config)) {
            return [];
        }

        return $this->findInStorage($modelClass, $config, false);
    }

    /**
     * @param $modelClass
     * @param $config
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
     * @param $modelClass
     * @param $whereConfig
     * @param bool $isGreedy
     * @return array
     */
    private function findInStorage($modelClass, $config, $isGreedy = true)
    {
        $result = [];

        foreach ($this->memoryStorage[$modelClass] as $item) {
            foreach ($config['where'] as $whereConfig) {
                switch ($whereConfig[0]) {
                    case 'all':
                        $result[] = $item;
                        break;
                    case '=':
                        if ($item[$whereConfig[1]] === $whereConfig[2]) {
                            if ($isGreedy) {
                                $result = $item;
                                break;
                            } else {
                                $result[] = $item;
                            }
                        }
                        break;
                }
            }
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    public function flush()
    {
        $result = false;

        /** @var VirtualModel $model */
        foreach ($this->persistedModels as $model) {
            $modelClass = get_class($model);

            if ($model->isNewRecord) {
                $this->memoryStorage[$modelClass][] = $model->getAttributes();
                $result = true;
            } else {
                if (!$model->hasAttribute('id')) {
                    throw new \Exception("Model $modelClass has no id attribute and cant be saved");
                }

                foreach ($this->memoryStorage[$modelClass] as $key => $possibleModel) {
                    if ($possibleModel['id'] === $model->id) {
                        $this->memoryStorage[$modelClass][$key] = $model->getAttributes();
                        $result = true;
                    }
                }
            }
        }

        parent::flush();

        return $result;
    }

    /**
     * @param VirtualModel $model
     * @return bool
     * @throws \Exception
     */
    public function delete(VirtualModel $model)
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
}
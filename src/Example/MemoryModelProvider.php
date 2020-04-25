<?php

namespace kosuha606\EnvironmentModel\Example;

use kosuha606\EnvironmentModel\EnvironmentModelProvider;

/**
 * Провайдер для хранения данных в памяти программы
 * @package kosuha606\EnvironmentModel\example
 */
class MemoryModelProvider extends EnvironmentModelProvider
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

}
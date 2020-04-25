<?php

use kosuha606\EnvironmentModel\EnvironmentModelManager;
use kosuha606\EnvironmentModel\Example\MemoryModelProvider;
use kosuha606\EnvironmentModel\Example\Shop\Product;

require_once __DIR__.'/../vendor/autoload.php';

$provider = new MemoryModelProvider();

$provider->memoryStorage = [
    Product::class => [
        [
            'name' => 'Апельсины',
            'price' => 50,
            'color' => 'Оранжевый',
            'size' => '20cm',
            'amount' => 100,
        ],
        [
            'name' => 'Яблоки',
            'price' => 30,
            'color' => 'Зеленый',
            'size' => '12cm',
            'amount' => 10,
        ],
        [
            'name' => 'Вишня',
            'price' => 130,
            'color' => 'Красный',
            'size' => '12cm',
            'amount' => 30,
        ],
    ]
];

EnvironmentModelManager::getInstance()->setProvider($provider);
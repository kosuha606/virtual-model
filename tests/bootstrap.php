<?php

use kosuha606\EnvironmentModel\EnvironmentModelManager;
use kosuha606\EnvironmentModel\Example\MemoryModelProvider;
use kosuha606\EnvironmentModel\Example\Shop\Category;
use kosuha606\EnvironmentModel\Example\Shop\Product;

require_once __DIR__.'/../vendor/autoload.php';

$provider = new MemoryModelProvider();

$provider->memoryStorage = [
    Category::class => [
        [
            'id' => 1,
            'name' => 'Фрукты',
        ],
        [
            'id' => 2,
            'name' => 'Ягоды',
        ],
    ],
    Product::class => [
        [
            'id' => 1,
            'name' => 'Апельсины',
            'price' => 50,
            'color' => 'Оранжевый',
            'size' => '20cm',
            'amount' => 100,
            'category_id' => 1,
        ],
        [
            'id' => 2,
            'name' => 'Яблоки',
            'price' => 30,
            'color' => 'Зеленый',
            'size' => '12cm',
            'amount' => 10,
            'category_id' => 1,
        ],
        [
            'id' => 3,
            'name' => 'Вишня',
            'price' => 130,
            'color' => 'Красный',
            'size' => '12cm',
            'amount' => 30,
            'category_id' => 2,
        ],
    ]
];

EnvironmentModelManager::getInstance()->setProvider($provider);
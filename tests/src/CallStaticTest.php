<?php

use kosuha606\VirtualModel\Example\MemoryModelProvider;
use kosuha606\VirtualModel\Example\Product\Category;
use kosuha606\VirtualModel\Example\Product\Product;
use kosuha606\VirtualModel\VirtualModelManager;
use PHPUnit\Framework\TestCase;

class CallStaticTest extends TestCase
{
    private $provider;

    public function setUp()
    {
        $this->provider = new MemoryModelProvider();

        $this->provider->memoryStorage = [
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

        VirtualModelManager::getInstance()->setProvider($this->provider);
    }

    public function tearDown()
    {
        unset($this->provider);
    }

    public function testOne()
    {
        $count = Product::count([
            'where' => [
                ['all']
            ]
        ]);

        $this->assertEquals(3, $count);
    }
}
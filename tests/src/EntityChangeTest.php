<?php

use kosuha606\VirtualModel\Example\MemoryModelProvider;
use kosuha606\VirtualModel\Example\Product\Category;
use kosuha606\VirtualModel\Example\Product\Product;
use kosuha606\VirtualModel\Test\ProductModified;
use kosuha606\VirtualModel\VirtualModelManager;
use PHPUnit\Framework\TestCase;

class EntityChangeTest extends TestCase
{
    private $provider;

    /**
     * @throws Exception
     */
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
            ProductModified::class => [
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
        VirtualModelManager::setEntities([
            Product::class => ProductModified::class,
        ]);
    }

    /**
     * @throws Exception
     */
    public function testOne()
    {
        $products = VirtualModelManager::getEntity(Product::class)::many([
            'where' => [
                ['=', 'id', 2]
            ]
        ]);

        $this->assertEquals(1, count($products));
        $this->assertInstanceOf(ProductModified::class, $products[0]);
    }
}
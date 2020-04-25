<?php

use kosuha606\EnvironmentModel\EnvironmentModelManager;
use kosuha606\EnvironmentModel\Example\MemoryModelProvider;
use kosuha606\EnvironmentModel\Example\Product\Category;
use kosuha606\EnvironmentModel\Example\Product\Product;
use kosuha606\EnvironmentModel\Example\Product\ProductsStatistic;
use PHPUnit\Framework\TestCase;

/**
 * уемые данные в bootstrap.php
 */
class ProductTest extends TestCase
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

        EnvironmentModelManager::getInstance()->setProvider($this->provider);
    }

    public function tearDown()
    {
        unset($this->provider);
    }

    /**
     * @throws Exception
     */
    public function testFirst()
    {
        $ps = new ProductsStatistic();
        $count = $ps->totalProductsAmount();
        $this->assertEquals(140, $count);

        $totalPrice = $ps->totalProductsPrice();
        $this->assertEquals(210, $totalPrice);

        $averageOnePrice = $ps->averageOneProductPrice();
        $this->assertEquals(1.5, $averageOnePrice);
    }

    public function testOneProduct()
    {
        /** @var Product $product */
        $product = EnvironmentModelManager::getInstance()->getProvider()->one(Product::class, [
            'where' => [
                ['=', 'id', 1]
            ]
        ]);

        $category = $product->getCategory();

        $this->assertEquals('Апельсины', $product->name);
        $this->assertEquals('Фрукты', $category->name);
    }
}
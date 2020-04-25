<?php

use kosuha606\EnvironmentModel\EnvironmentModelManager;
use kosuha606\EnvironmentModel\Example\Shop\Product;
use kosuha606\EnvironmentModel\Example\Shop\ProductsStatistic;
use PHPUnit\Framework\TestCase;

/**
 * Тестируемые данные в bootstrap.php
 */
class ProductTest extends TestCase
{
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
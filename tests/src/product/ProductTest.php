<?php

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
}
<?php

namespace kosuha606\EnvironmentModel\Example\Product;

use kosuha606\EnvironmentModel\VirtualModelManager;

class ProductsStatistic
{
    /**
     * Общее кол-во продуктов
     * @return int
     * @throws \Exception
     */
    public function totalProductsAmount()
    {
        /** @var Product[] $products */
        $products = Product::many([
            'where' => [
                ['all']
            ]
        ]);
        $amount = 0;

        foreach ($products as $product) {
            $amount += $product->amount;
        }

        return $amount;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function totalProductsPrice()
    {
        /** @var Product[] $products */
        $products = Product::many([
            'where' => [
                ['all']
            ]
        ]);
        $price = 0;

        foreach ($products as $product) {
            $price += $product->price;
        }

        return $price;
    }

    /**
     * Средняя цена на один продукт
     * @return float|int
     * @throws \Exception
     */
    public function averageOneProductPrice()
    {
        $totalPrice = $this->totalProductsPrice();
        $totalCount = $this->totalProductsAmount();

        $result = 0;

        if ($totalCount !== 0) {
            $result = $totalPrice / $totalCount;
        }

        return $result;
    }
}
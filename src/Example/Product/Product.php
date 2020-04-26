<?php

namespace kosuha606\EnvironmentModel\Example\Product;

use kosuha606\EnvironmentModel\VirtualModel;
use kosuha606\EnvironmentModel\VirtualModelManager;

/**
 * @property $amount
 * @property $price
 * @property $category_id
 * @property $name
 */
class Product extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'name',
            'price',
            'color',
            'size',
            'amount',
            'category_id',
        ];
    }

    /**
     * @return Category|null
     * @throws \Exception
     */
    public function getCategory()
    {
        return Category::one([
            'where' => [
                ['=', 'id', $this->category_id]
            ]
        ]);
    }
}
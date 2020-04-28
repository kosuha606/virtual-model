<?php

namespace kosuha606\VirtualModel\Example\Product;

use kosuha606\VirtualModel\VirtualModel;
use kosuha606\VirtualModel\VirtualModelManager;

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
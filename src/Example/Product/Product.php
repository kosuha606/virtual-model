<?php

namespace kosuha606\VirtualModel\Example\Product;

use kosuha606\VirtualModel\VirtualModelEntity;
use kosuha606\VirtualModel\VirtualModelManager;

/**
 * @property int $amount
 * @property float $price
 * @property int $category_id
 * @property string $name
 */
class Product extends VirtualModelEntity
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
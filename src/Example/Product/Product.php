<?php

namespace kosuha606\EnvironmentModel\Example\Product;

use kosuha606\EnvironmentModel\EnvironmentModel;
use kosuha606\EnvironmentModel\EnvironmentModelManager;

/**
 * @property $amount
 * @property $price
 * @property $category_id
 * @property $name
 */
class Product extends EnvironmentModel
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
     */
    public function getCategory()
    {
        /** @var Category $category */
        $category = EnvironmentModelManager::getInstance()->getProvider()->one(Category::class, [
            'where' => [
                ['=', 'id', $this->category_id]
            ]
        ]);

        return $category;
    }
}
<?php

namespace kosuha606\EnvironmentModel\Example\Shop;

use kosuha606\EnvironmentModel\EnvironmentModel;

/**
 * @property $amount
 * @property $price
 */
class Product extends EnvironmentModel
{
    public function attributes(): array
    {
        return [
            'name',
            'price',
            'color',
            'size',
            'amount',
        ];
    }
}
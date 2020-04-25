<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\EnvironmentModel;

/**
 * Остаток по продукту
 * @package kosuha606\Model\iteration2\model
 * Остаток по продукту
 */
class ProductRests extends EnvironmentModel
{
    public function attributes(): array
    {
        return [
            'productId',
            'qty',
            'userType',
        ];
    }
}
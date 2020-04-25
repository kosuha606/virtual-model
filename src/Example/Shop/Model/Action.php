<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\EnvironmentModel;

/**
 * Акция для продукта
 * @package kosuha606\Model\iteration2\model
 */
class Action extends EnvironmentModel
{
    public function attributes(): array
    {
        return [
            'id',
            'productId',
            'percent',
            'userType',
        ];
    }
}
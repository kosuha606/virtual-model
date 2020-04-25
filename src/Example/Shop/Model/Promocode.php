<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\EnvironmentModel;

/**
 * Промокод для корзины
 * @package kosuha606\Model\iteration2\model
 */
class Promocode extends EnvironmentModel
{
    public function attributes(): array
    {
        return [
            'id',
            'amount',
            'code',
            'userType',
        ];
    }
}
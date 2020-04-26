<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\VirtualModel;

/**
 * Промокод для корзины
 * @package kosuha606\Model\iteration2\model
 */
class Promocode extends VirtualModel
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
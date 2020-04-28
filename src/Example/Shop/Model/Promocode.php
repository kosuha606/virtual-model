<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModel;

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
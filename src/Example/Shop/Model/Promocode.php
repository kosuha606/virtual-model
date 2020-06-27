<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * Промокод для корзины
 * @package kosuha606\Model\iteration2\model
 */
class Promocode extends VirtualModelEntity
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
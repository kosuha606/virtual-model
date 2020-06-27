<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * Вариант доставки
 * @package kosuha606\Model\iteration2\model
 */
class Delivery extends VirtualModelEntity
{
    public function attributes(): array
    {
        return [
            'id',
            'price',
            'description',
            'userType',
        ];
    }
}
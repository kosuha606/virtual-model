<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * Акция для продукта
 * @package kosuha606\Model\iteration2\model
 */
class Action extends VirtualModelEntity
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
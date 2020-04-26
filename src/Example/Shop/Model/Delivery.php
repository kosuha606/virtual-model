<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\VirtualModel;

/**
 * Вариант доставки
 * @package kosuha606\Model\iteration2\model
 */
class Delivery extends VirtualModel
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
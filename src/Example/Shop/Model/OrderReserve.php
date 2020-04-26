<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\VirtualModel;

/**
 * Резерв продуктов в заказах
 * @package kosuha606\Model\iteration2\model
 */
class OrderReserve extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'orderId',
            'productId',
            'qty',
            'userType',
        ];
    }
}
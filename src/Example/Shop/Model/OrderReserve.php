<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModel;

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
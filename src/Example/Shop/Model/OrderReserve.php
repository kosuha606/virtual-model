<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * Резерв продуктов в заказах
 * @package kosuha606\Model\iteration2\model
 *
 * @property int $productId
 * @property int $id
 * @property int $qty
 */
class OrderReserve extends VirtualModelEntity
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
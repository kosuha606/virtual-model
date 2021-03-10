<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * Вариант оплаты
 * @package kosuha606\Model\iteration2\model
 * @property int $comission
 * @property float $price
 * @property int $amount
 */
class Payment extends VirtualModelEntity
{
    public function attributes(): array
    {
        return [
            'id',
            'comission',
            'description',
            'userType',
        ];
    }
}
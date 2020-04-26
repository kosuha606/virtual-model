<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\VirtualModel;

/**
 * Вариант оплаты
 * @package kosuha606\Model\iteration2\model
 */
class Payment extends VirtualModel
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
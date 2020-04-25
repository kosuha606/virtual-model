<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\EnvironmentModel;

/**
 * Вариант оплаты
 * @package kosuha606\Model\iteration2\model
 */
class Payment extends EnvironmentModel
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
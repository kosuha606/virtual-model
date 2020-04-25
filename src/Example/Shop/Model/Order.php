<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\EnvironmentModel;

class Order extends EnvironmentModel
{
    public function attributes(): array
    {
        return [
            'id',
            'items',
            'userType',
            'reserve',
        ];
    }
}
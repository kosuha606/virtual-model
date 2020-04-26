<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\VirtualModel;

class Order extends VirtualModel
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
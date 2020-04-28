<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModel;

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
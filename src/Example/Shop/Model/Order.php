<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

class Order extends VirtualModelEntity
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
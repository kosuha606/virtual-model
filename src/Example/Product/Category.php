<?php

namespace kosuha606\VirtualModel\Example\Product;

use kosuha606\VirtualModel\VirtualModel;

class Category extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'name'
        ];
    }
}
<?php

namespace kosuha606\EnvironmentModel\Example\Product;

use kosuha606\EnvironmentModel\VirtualModel;

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
<?php

namespace kosuha606\EnvironmentModel\Example\Shop;

use kosuha606\EnvironmentModel\EnvironmentModel;

class Category extends EnvironmentModel
{
    public function attributes(): array
    {
        return [
            'id',
            'name'
        ];
    }
}
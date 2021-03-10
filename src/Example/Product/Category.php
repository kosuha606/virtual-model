<?php

namespace kosuha606\VirtualModel\Example\Product;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * @property int $id
 * @property string $name
 */
class Category extends VirtualModelEntity
{
    public function attributes(): array
    {
        return [
            'id',
            'name'
        ];
    }
}
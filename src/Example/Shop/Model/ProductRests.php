<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModel;

/**
 * Остаток по продукту
 * @package kosuha606\Model\iteration2\model
 * Остаток по продукту
 */
class ProductRests extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'productId',
            'qty',
            'userType',
        ];
    }
}
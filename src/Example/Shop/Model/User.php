<?php

namespace kosuha606\VirtualModel\Example\Shop\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * Пользователь
 * @package kosuha606\Model\iteration2\model
 *
 * @property string $role
 */
class User extends VirtualModelEntity
{
    public function attributes(): array
    {
        return [
            'id',
            'name',
            'role',
            'personalDiscount',
        ];
    }

    public function isB2C()
    {
        return $this->role === 'b2c';
    }

    public function isB2B()
    {
        return $this->role === 'b2b';
    }
}
<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\EnvironmentModel;

/**
 * Пользователь
 * @package kosuha606\Model\iteration2\model
 */
class User extends EnvironmentModel
{
    public $id;

    public $name;

    public $role;

    public $personalDiscount = 0;

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
<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

class User extends VirtualModelEntity
{
    public function attributes(): array
    {
        return [
            'id',
            'name',
            'email',
            'last_login',
            'online',
            'password'
        ];
    }
}
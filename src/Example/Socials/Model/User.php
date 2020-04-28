<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModel;

class User extends VirtualModel
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
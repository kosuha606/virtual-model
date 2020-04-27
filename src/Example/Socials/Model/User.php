<?php

namespace kosuha606\EnvironmentModel\Example\Socials\Model;

use kosuha606\EnvironmentModel\VirtualModel;

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
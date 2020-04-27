<?php

namespace kosuha606\EnvironmentModel\Example\Socials\Model;

use kosuha606\EnvironmentModel\VirtualModel;

class UserGroup extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'user_id',
            'group_id',
        ];
    }
}
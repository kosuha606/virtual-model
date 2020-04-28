<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModel;

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
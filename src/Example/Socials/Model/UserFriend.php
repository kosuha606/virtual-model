<?php

namespace kosuha606\EnvironmentModel\Example\Socials\Model;

use kosuha606\EnvironmentModel\VirtualModel;

class UserFriend extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'user_id',
            'friend_id',
        ];
    }
}
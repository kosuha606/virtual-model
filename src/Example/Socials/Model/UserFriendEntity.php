<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

class UserFriend extends VirtualModelEntity
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
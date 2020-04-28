<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModel;

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
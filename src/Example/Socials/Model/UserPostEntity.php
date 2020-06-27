<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

class UserPost extends VirtualModelEntity
{
    public function attributes(): array
    {
        return [
            'id',
            'user_id',
            'post_id',
        ];
    }
}
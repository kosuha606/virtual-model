<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModel;

class UserPost extends VirtualModel
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
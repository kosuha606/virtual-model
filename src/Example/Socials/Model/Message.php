<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModel;

class Message extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'content',
            'created_at',
            'to_user_id',
        ];
    }
}
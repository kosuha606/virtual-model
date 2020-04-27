<?php

namespace kosuha606\EnvironmentModel\Example\Socials\Model;

use kosuha606\EnvironmentModel\VirtualModel;

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
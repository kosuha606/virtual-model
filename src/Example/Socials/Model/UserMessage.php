<?php

namespace kosuha606\EnvironmentModel\Example\Socials\Model;

use kosuha606\EnvironmentModel\VirtualModel;

class UserMessage extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'user_id',
            'message_id',
        ];
    }
}
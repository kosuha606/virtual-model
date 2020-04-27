<?php

namespace kosuha606\EnvironmentModel\Example\Socials\Model;

use kosuha606\EnvironmentModel\VirtualModel;

class Post extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'content',
            'created_at',
        ];
    }
}
<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

class Post extends VirtualModelEntity
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
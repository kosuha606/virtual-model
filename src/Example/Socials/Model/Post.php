<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModel;

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
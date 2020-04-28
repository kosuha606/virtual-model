<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModel;

class PostGroup extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'post_id',
            'group_id',
        ];
    }
}
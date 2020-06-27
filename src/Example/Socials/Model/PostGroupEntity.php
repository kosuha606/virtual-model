<?php

namespace kosuha606\VirtualModel\Example\Socials\Model;

use kosuha606\VirtualModel\VirtualModelEntity;

class PostGroup extends VirtualModelEntity
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
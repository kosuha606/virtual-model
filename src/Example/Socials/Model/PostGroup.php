<?php

namespace kosuha606\EnvironmentModel\Example\Socials\Model;

use kosuha606\EnvironmentModel\VirtualModel;

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
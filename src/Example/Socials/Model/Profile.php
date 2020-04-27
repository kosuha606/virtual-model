<?php

namespace kosuha606\EnvironmentModel\Example\Socials\Model;

use kosuha606\EnvironmentModel\VirtualModel;

class Profile extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'user_id',
            'about',
        ];
    }
}
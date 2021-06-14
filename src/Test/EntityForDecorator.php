<?php

namespace kosuha606\VirtualModel\Test;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * @property $name
 */
class EntityForDecorator extends VirtualModelEntity
{
    const KEY = 'adapter';

    public static function providerType(): string
    {
        return self::KEY;
    }

    public function attributes(): array
    {
        return [
            'id',
            'name',
        ];
    }
}
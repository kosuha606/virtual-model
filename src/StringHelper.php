<?php

namespace kosuha606\VirtualModel;

class StringHelper
{
    /**
     * @param string $snakeString
     * @param string $environment
     * @return mixed|string
     */
    public static function normalizeEnvMethod(string $snakeString, string $environment): string
    {
        return self::snakeToCamel($snakeString.'_'.$environment);
    }

    /**
     * @param string $string
     * @param bool $capitalizeFirstCharacter
     * @return mixed|string
     */
    public static function snakeToCamel(string $string, bool $capitalizeFirstCharacter = false): string
    {
        $result = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $result = lcfirst($result);
        }

        return $result;
    }
}

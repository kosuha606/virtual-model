<?php

namespace kosuha606\VirtualModel;

class StringHelper
{
    /**
     * @param string $snakeString
     * @return mixed|string
     */
    public static function normalizeEnvMethod($snakeString, $environment)
    {
        $camelString = self::snakeToCamel($snakeString.'_'.$environment);

        return $camelString;
    }

    /**
     * @param string $string
     * @param bool $capitalizeFirstCharacter
     * @return mixed|string
     */
    public static function snakeToCamel($string, $capitalizeFirstCharacter = false)
    {
        $result = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $result = lcfirst($result);
        }

        return $result;
    }
}
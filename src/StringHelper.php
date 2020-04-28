<?php

namespace kosuha606\VirtualModel;

/**
 * Class StringHelper
 * @package kosuha606\VirtualModel
 */
class StringHelper
{
    /**
     * @param $snakeString
     * @return mixed|string
     */
    public static function normalizeEnvMethod($snakeString, $environment)
    {
        $camelString = self::snakeToCamel($snakeString.'_'.$environment);

        return $camelString;
    }

    /**
     * @param $string
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
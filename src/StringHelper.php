<?php

namespace kosuha606\EnvironmentModel;

/**
 * Class StringHelper
 * @package kosuha606\EnvironmentModel
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
        $str = str_replace('-', '', ucwords($string, '-'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }
}
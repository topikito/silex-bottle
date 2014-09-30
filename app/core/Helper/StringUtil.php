<?php

namespace Topikito\Acme\Helper;

/**
 * Class StringUtil
 *
 * @package Topikito\Acme\Helper
 */
class StringUtil
{
    /**
     * @param $str
     *
     * @return string
     */
    public static function fromCamelCaseToUnderScore($str)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $str));
    }

    /**
     * @param $str
     *
     * @return mixed
     */
    public static function fromUnderScoreToCamelCase($str)
    {
        $str = str_replace(' ', '' ,ucwords(str_replace('_', ' ', $str)));
        $str[0] = strtolower($str[0]);
        return $str;
    }
    public static function trimWithEllipsis($string, $size)
    {
        if (strlen($string) > $size) {
            $string = substr($string, 0, $size);
            $lastSpace = strrpos($string, ' ');
            $string = substr($string, 0, $lastSpace);
            $string .= '…';
        }

        return $string;
    }

}

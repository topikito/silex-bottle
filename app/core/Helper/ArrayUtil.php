<?php

namespace Topikito\Acme\Helper;

/**
 * Class ArrayUtil
 *
 * @package Topikito\Acme\Helper
 */
class ArrayUtil
{
    /**
     * @param $arr
     *
     * @return bool
     */
    public static function validArray($arr)
    {
        return (!empty($arr) && is_array($arr));
    }

}

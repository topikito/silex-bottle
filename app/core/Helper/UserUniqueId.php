<?php

namespace Topikito\Acme\Helper;

/**
 * Class UserUniqueId
 */
class UserUniqueId {

    /**
     *
     */
    const UID_SUFFIX = '::foo::bar::bazz';

    /**
     * @param $string
     *
     * @return string
     */
    static function generate($string) {
        return md5($string . self::UID_SUFFIX);
    }

    /**
     * @param $email
     * @param $hash
     *
     * @return bool
     */
    static function checkEqual($email, $hash) {
        return (self::generate($email) == $hash);
    }
}
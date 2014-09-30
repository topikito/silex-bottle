<?php

namespace app\Core\Plugin\Handler;

use app\Core\Bridge\BasePlugin;

/**
 * Class CacheHandler
 *
 * @package app\Core\Plugin\Handler
 */
class CacheHandler extends BasePlugin
{
    const CACHE_CONFIG_NAME = 'cache';
    const CACHE_OBJECT_NAME = 'redis';

    const DEFAULT_ENABLED = false;

    /**
     * @var \Redis
     */
    protected $_cacheObject = null;

    /**
     * @param \Silex\Application $app
     */
    public function __construct($app)
    {
        $this->_app = $app;

        $enabled = (isset($app['config'][self::CACHE_CONFIG_NAME . '.enabled']))? $app['config'][self::CACHE_CONFIG_NAME . '.enabled'] : self::DEFAULT_ENABLED;

        if (!$enabled) {
            $this->_cacheObject = false;
        }
    }

    /**
     * @return null|bool|\Redis
     */
    protected function _getCacheObject()
    {
        if ($this->_cacheObject === null) {
            $this->_cacheObject = $this->_app[self::CACHE_OBJECT_NAME . '.object'];
        }

        return $this->_cacheObject;
    }

    /**
     * @param $key      string
     * @param $value    mixed
     * @param $ttl      int
     *
     * @return bool|null
     */
    public function set($key, $value, $ttl)
    {
        $result = null;

        $cacheObject = $this->_getCacheObject();
        if ($cacheObject) {
            $result = $cacheObject->setex($key, $ttl, $value);
        }

        return $result;
    }

    /**
     * @param $key
     *
     * @return bool|null|string
     */
    public function get($key)
    {
        $result = null;

        $cacheObject = $this->_getCacheObject();
        if ($cacheObject) {
            $result = $cacheObject->get($key);
        }

        return $result;
    }

    /**
     * @param $key
     *
     * @return int|null
     */
    public function del($key)
    {
        $result = null;

        $cacheObject = $this->_getCacheObject();
        if ($cacheObject) {
            $result = $cacheObject->del($key);
        }

        return $result;
    }

}

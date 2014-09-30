<?php

namespace app\Core\SAL;

use Silex\Application;
use app\Core\Plugin\Handler;

/**
 * Class DataMapper
 *
 * @package app\core\SAL
 */
abstract class BaseDataMapper
{
    /**
     * @var \Silex\Application
     */
    protected $_app;

    /**
     * @var null
     */
    protected $_cacheKeyPrefix = null;
    /**
     * @var array
     */
    protected $_cacheVersion = [];
    /**
     * @var int
     */
    protected $_ttl = 108000; //60 * 60 * 30

    /**
     * @var \app\Core\Plugin\Handler\CacheHandler
     */
    protected $_cacheHandler;

    /**
     * @param Application $_app
     */
    public function __construct(Application $_app)
    {
        $this->_app = $_app;

        $this->_cacheHandler = new Handler\CacheHandler($this->_app);
    }

    /*
     * ABSTRACT METHODS
     */

    /**
     * @param $entityId
     * @param $retrieveData
     *
     * @return mixed
     */
    abstract public function getData($entityId, $retrieveData);

    /**
     * @param $entityId
     * @param $data
     *
     * @return mixed
     */
    abstract public function setData($entityId, $data);

    /**
     * @param      $entityId
     * @param      $key
     * @param null $result
     *
     * @return mixed
     */
    abstract public function refresh($entityId, $key, $result = null);

    /*
     * SHARED METHODS
     */

    /**
     * @param $entityId
     * @param $key
     *
     * @return mixed|null
     */
    protected function _getNode($entityId, $key)
    {
        $cacheData = $this->_cacheData($entityId, $key);

        if ($cacheData) {
            $result = $cacheData;
        } else {
            $result = $this->refresh($entityId, $key);
        }

        return $result;
    }

    /*
     * CACHE METHODS
     */

    /**
     *
     * Generates a string with the form: <prefix><glue><key><glue><version><glue><id>
     * Example:
     *  - userData:user:1:54
     *
     * @param $entityId integer
     * @param $key string
     * @return string
     */
    private function _buildObjectName($entityId,$key)
    {
        $glue = ':';
        return $this->_cacheKeyPrefix . $glue . $key . $glue . $this->_cacheVersion[$key] . $glue . $entityId;
    }

    /**
     * Right now not performing any change, but could be used to filter the output
     *
     * @param null $result
     * @return mixed
     */
    private function _parseResult($result = null)
    {
        return $result;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    private function _cacheLoadData($key)
    {
        $result = $this->_cacheHandler->get($key);
        return $this->_parseResult($result);
    }

    /**
     * @param      $key
     * @param      $values
     * @param null $ttl
     *
     * @return mixed
     */
    private function _cacheSaveData($key, $values, $ttl = null)
    {
        $result = $this->_cacheHandler->set($key,$values, $ttl);
        return $this->_parseResult($result);
    }

    /**
     * @param      $entityId
     * @param      $key
     * @param null $value
     *
     * @return mixed|null
     */
    protected function _cacheData($entityId, $key, $value = null)
    {
        if (!$this->_app['cache']) {
            return $this->_parseResult();
        }

        $originalKey = $key;

        $result = null;
        $key = $this->_buildObjectName($entityId, $key);
        if (is_null($value)) {
            $result = $this->_cacheLoadData($key);
        } else {
            $ttl = null;
            if (isset($this->_ttl[$originalKey]) && !empty($this->_ttl[$originalKey])) {
                $ttl = $this->_ttl[$originalKey];
            }

            $result = $this->_cacheSaveData($key, $value, $ttl);
        }
        return $result;
    }

    /**
     * @param $entityId
     * @param $key
     *
     * @return int|null
     */
    public function invalidateCache($entityId, $key)
    {
        $key = $this->_buildObjectName($entityId, $key);
        $result = $this->_cacheHandler->del($key);

        return $result;
    }

}

<?php

namespace Topikito\Acme\Config\Service;

use app\config;
use Silex\Provider\HttpCacheServiceProvider;

/**
 * Class HttpCache
 *
 * @package app\config\Service
 */
class HttpCache extends config\Bridges\Service
{

    public function load()
    {
        $options = [
            'http_cache.cache_dir' => $this->_tempFolder . 'cache/',
            'http_cache.esi'       => null
        ];

        $this->_app->register(new HttpCacheServiceProvider(), $options);
    }

}

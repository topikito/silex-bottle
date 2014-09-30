<?php

namespace app\Config\ServiceLoader;

use app\Config\Bridge\BaseServiceLoader;
use Silex\Provider\HttpCacheServiceProvider;

/**
 * Class BaseHttpCacheBaseServiceLoader
 *
 * @package app\config\Service
 */
class BaseHttpCacheServiceLoader extends BaseServiceLoader
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

<?php

namespace app\Config\ServiceLoader;

use app\Config\Bridge\BaseServiceLoader;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;

/**
 * Class BaseWebProfilerService
 *
 * @package app\config\Service
 */
class BaseWebProfilerServiceLoader extends BaseServiceLoader
{

    public function load()
    {
        if ($this->_app['config']['app.debug.mode']) {
            $options = [
                'profiler.cache_dir' => $this->_tempFolder . 'cache/profiler',
                'profiler.mount_prefix' => '/_profiler', // this is the default
            ];

            $this->_app->register(new ServiceControllerServiceProvider());
            $this->_app->register(new UrlGeneratorServiceProvider());
            $this->_app->register(new WebProfilerServiceProvider(), $options);
        }

    }

}

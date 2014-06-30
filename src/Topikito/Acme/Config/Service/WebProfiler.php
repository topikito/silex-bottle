<?php

namespace Topikito\Acme\Config\Service;

use app\config;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;

/**
 * Class WebProfiler
 *
 * @package app\config\Service
 */
class WebProfiler extends config\Bridges\Service
{

    public function load()
    {
        if ($this->_app['config']['debug.mode']) {
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

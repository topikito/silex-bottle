<?php

namespace Topikito\Acme\Config;

use app\Config\Bridge\BaseConfigLoader;
use app\Config\Bridge\BaseInstructor;
use Topikito\Acme\Config;

class Instructor extends BaseInstructor
{

    public function prepareConfigs()
    {
        $configs = [
            new BaseConfigLoader($this->_app, __NAMESPACE__)
        ];

        $this->_addConfigs($configs);
    }

    public function prepareRoutes()
    {
        $routes = [
            new Config\Route\Home($this->_app)
        ];

        $this->_addRoutes($routes);
    }

    public function prepareServices()
    {
        $services = [
            new Config\ServiceLoader\TwigServiceLoader($this->_app),
            new Config\ServiceLoader\TranslatorServiceLoader($this->_app),
            new Config\ServiceLoader\HttpCacheServiceLoader($this->_app),
            new Config\ServiceLoader\SessionServiceLoader($this->_app),
            new Config\ServiceLoader\DoctrineServiceLoader($this->_app),
            new Config\ServiceLoader\SecurityServiceLoader($this->_app),
            new Config\ServiceLoader\MonologServiceLoader($this->_app),
            new Config\ServiceLoader\WebProfilerServiceLoader($this->_app),
            new Config\ServiceLoader\RedisServiceLoader($this->_app),
            new Config\ServiceLoader\ElasticSearchServiceLoader($this->_app)
        ];

        $this->_addServices($services);
    }

    public function prepareMiddlewares()
    {
        $middlewares = [
            new Config\Middleware\ErrorHandler($this->_app)
        ];

        $this->_addMiddlewares($middlewares);
    }

}

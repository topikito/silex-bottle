<?php

namespace Topikito\Acme\Config;

use app\config;
use Topikito\Acme\Config\Route;

class Instructor extends config\Bridges\Instructor
{

    public function prepareConfigs()
    {
        $configs = [
            new config\Bridges\ConfigLoader($this->_app, __NAMESPACE__)
        ];

        $this->_addConfigs($configs);
    }

    public function prepareRoutes()
    {
        $routes = [
            new Route\Home($this->_app)
        ];

        $this->_addRoutes($routes);
    }

    public function prepareServices()
    {
        $services = [
            new Service\Twig($this->_app),
            new Service\Translator($this->_app),
            new Service\HttpCache($this->_app),
            new Service\Session($this->_app),
            new Service\Doctrine($this->_app),
            new Service\Security($this->_app),
            new Service\Monolog($this->_app),
            new Service\WebProfiler($this->_app)
        ];

        $this->_addServices($services);
    }

}

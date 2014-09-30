<?php

namespace app\Config\Bridge;

use app\config\Interfaces\Loadable;
use Silex\Application;

/**
 * Class Instructor
 *
 * @package app\config\Bridges
 */
abstract class BaseInstructor implements Loadable
{

    /**
     * @var \Silex\Application
     */
    protected $_app;

    private $_routes;
    private $_services;
    private $_configs;
    private $_middlewares;

    public function __construct(Application $_app)
    {
        $this->_app         = $_app;

        $this->_routes      = [];
        $this->_services    = [];
        $this->_configs     = [];
        $this->_middlewares = [];
    }

    /**
     * @return mixed
     */
    abstract public function prepareRoutes();

    /**
     * @return mixed
     */
    abstract public function prepareServices();

    /**
     * @return mixed
     */
    abstract public function prepareConfigs();

    /**
     * @return mixed
     */
    abstract public function prepareMiddlewares();

    /**
     * @param $routes
     *
     * @return bool
     */
    protected function _addRoutes($routes)
    {
        $this->_routes = array_merge($this->_routes, $routes);
        return $this;
    }

    /**
     * @param $services
     *
     * @return bool
     */
    protected function _addServices($services)
    {
        $this->_services = array_merge($this->_services, $services);
        return $this;
    }

    /**
     * @param $configs
     *
     * @return bool
     */
    protected function _addConfigs($configs)
    {
        $this->_configs = array_merge($this->_configs, $configs);
        return $this;
    }

    /**
     * @param $middlewares
     *
     * @return bool
     */
    protected function _addMiddlewares($middlewares)
    {
        $this->_middlewares = array_merge($this->_middlewares, $middlewares);
        return $this;
    }

    public function loadRoutes()
    {
        foreach ($this->_routes as $route) {
            /**
             * @var $route \app\config\Bridge\Router
             */
            $route->load();
        }
    }

    public function loadServices()
    {
        foreach ($this->_services as $service) {
            /**
             * @var $service \app\config\Bridge\BaseServiceLoader
             */
            $service->load();
        }
    }

    public function loadConfigs()
    {
        foreach ($this->_configs as $config) {
            /**
             * @var $service \app\config\Bridge\BaseConfigLoader
             */
            $config->load();
        }
    }

    public function loadMiddlewares()
    {
        foreach ($this->_middlewares as $middleware) {
            /**
             * @var $service \app\config\Bridge\BaseConfigLoader
             */
            $middleware->load();
        }
    }

    /**
     * Default loader
     */
    public function load()
    {
        $this->prepareConfigs();
        $this->prepareMiddlewares();
        $this->prepareServices();
        $this->prepareRoutes();

        $this->loadConfigs();
        $this->loadMiddlewares();
        $this->loadServices();
        $this->loadRoutes();
    }

}

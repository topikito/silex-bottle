<?php

namespace app\config\Bridges;

use app\config\Interfaces\Loadable;
use Silex\Application;

/**
 * Class Instructor
 *
 * @package app\config\Bridges
 */
abstract class Instructor implements Loadable
{

    /**
     * @var \Silex\Application
     */
    protected $_app;

    private $_routes;
    private $_services;
    private $_configs;

    public function __construct(Application $_app)
    {
        $this->_app         = $_app;

        $this->_routes      = [];
        $this->_services    = [];
        $this->_configs     = [];
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

    public function loadRoutes()
    {
        foreach ($this->_routes as $route) {
            /**
             * @var $route \app\config\Bridges\Router
             */
            $route->load();
        }
    }

    public function loadServices()
    {
        foreach ($this->_services as $service) {
            /**
             * @var $service \app\config\Bridges\Service
             */
            $service->load();
        }
    }

    public function loadConfigs()
    {
        foreach ($this->_configs as $config) {
            /**
             * @var $service \app\config\Bridges\ConfigLoader
             */
            $config->load();
        }
    }

    /**
     * Default loader
     */
    public function load()
    {
        $this->prepareConfigs();
        $this->prepareServices();
        $this->prepareRoutes();

        $this->loadConfigs();
        $this->loadServices();
        $this->loadRoutes();
    }

}
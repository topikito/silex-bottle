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

    public function __construct(Application $_app)
    {
        $this->_app         = $_app;

        $this->_routes      = [];
        $this->_services    = [];
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
     *
     */
    public function loadRoutes()
    {
        foreach ($this->_routes as $route) {
            /**
             * @var $route \app\config\Bridges\Router
             */
            $route->load();
        }
    }

    /**
     *
     */
    public function loadServices()
    {
        foreach ($this->_services as $service) {
            /**
             * @var $service \app\config\Bridges\Service
             */
            $service->load();
        }
    }

    /**
     * Default loader
     */
    public function load()
    {
        $this->prepareServices();
        $this->prepareRoutes();

        $this->loadServices();
        $this->loadRoutes();
    }

}

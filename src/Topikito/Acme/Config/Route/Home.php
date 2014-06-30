<?php

namespace Topikito\Acme\Config\Route;

use app\config\Bridges\Router;
use Silex\Application;

/**
 * Class Home
 *
 * @package app\config\Route
 */
class Home extends Router
{

    /**
     * @var \Silex\Application
     */
    protected $_app;

    public function __construct($_app)
    {
        $this->_app = $_app;
    }

    public function load()
    {
        $this->_app->match('/', function () {
                $controller = new \Topikito\Acme\Controller\Home($this->_app);
                return $controller->index();
            });
    }

}

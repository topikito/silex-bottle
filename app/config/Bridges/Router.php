<?php

namespace app\config\Bridges;

use app\config\Interfaces\Loadable;
use Silex;

abstract class Router implements Loadable
{

    /**
     *
     * @var Silex\Application
     */
    protected $_app;

    /**
     * @param \Silex\Application $app
     */
    public function __construct(Silex\Application $app)
    {
        $this->_app = $app;
    }

}

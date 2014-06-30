<?php

namespace app\core;

use Silex\Application;

/**
 * Class View
 *
 * @package app\core
 */
class View
{

    /**
     * @var \Silex\Application
     */
    protected $_app;

    /**
     * @param Application $app
     * @param array       $params
     */
    public function __construct(Application $app, $params = [])
    {
        $this->_app = $app;
    }

}

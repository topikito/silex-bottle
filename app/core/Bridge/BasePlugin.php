<?php

namespace app\Core\Bridge;

abstract class BasePlugin
{
    /**
     * @var \Silex\Application
     */
    protected $_app;

    /**
     * @param $app \Silex\Application
     */
    public function __construct($app)
    {
        $this->_app = $app;
    }

}

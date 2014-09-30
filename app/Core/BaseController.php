<?php

namespace app\Core;

use Topikito\Acme\Helper;
use Silex\Application;

class BaseController
{
    /**
     *
     * @var \Silex\Application
     */
    protected $_app;

    /**
     * @var BaseView
     */
    public $view;

    public function __construct(Application $app)
    {
        $this->_app = $app;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function _getRequest()
    {
        return $this->_app['request'];
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function initView($params = [])
    {
        $this->view = new BaseView($this->_app, $params);

        $this->view->setParams($params);
        return true;
    }

}

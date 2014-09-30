<?php

namespace Topikito\Acme\Controller;

use app\Core;
use Topikito\Acme\Plugin\AuthUser;
use Topikito\Acme\Sal;
use Silex\Application;

/**
 * Class Home
 *
 * @package Topikito\Acme\Controller
 */
class Home extends Core\BaseController
{

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->initView([
                'pageTitle' => 'Acme.',
                'pageDescription' => 'Acme foo bar baz.',
            ]);
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->view->render('Home/index.html.twig');
    }

}

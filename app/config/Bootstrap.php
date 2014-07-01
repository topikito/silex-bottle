<?php

namespace app\config;

require_once 'autoload.php';

use app\config;
use Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApplicationBootstrap
 */
class Bootstrap
{
    /**
     * @var Silex\Application
     */
    protected $_app;

    protected $_env;

    /**
     * @param string $env
     */
    public function __construct($env = 'production')
    {
        $this->_app = new Silex\Application();
        $this->_app['env.name'] = $env;

        $this->_loadEnvironment();
    }

    protected function _loadEnvironment()
    {
        //TODO: Try and see if we can improve this as its surely a very slow solution
        $commitHash = $commitId = '';
        if (php_sapi_name() != 'cli') {
            $lastCommit = exec('git rev-parse HEAD');

            $commitHash = $lastCommit;
            $commitId = substr($lastCommit, -6);
        }

        $this->_app['env'] = [
            'commit.hash' => $commitHash,
            'commit.id' => $commitId
        ];
    }

    public function loadInstructors()
    {
        $instructors = [
            new \Topikito\Acme\Config\Instructor($this->_app)
        ];

        foreach ($instructors as $instructor) {
            /**
             * @var $instructor \app\config\Bridges\Instructor
             */
            $instructor->load();
        }

    }

    /**
     * Middleware loading
     */
    public function loadMiddleware()
    {
        /* BEFORE APP STARTS */
        $this->_app->before(function (Request $request) { });

        /* BEFORE APP FINISHES */
        $this->_app->after(function (Request $request, Response $response) { });
    }

    /**
     * @return \Silex\Application
     */
    public function getApplication()
    {
        return $this->_app;
    }

    /**
     * @return Silex\Application
     */
    public function ignition()
    {
        $this->loadMiddleware();
        $this->loadInstructors();

        return $this->getApplication();
    }

}

/**
 * Define application environment
 */
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

$app = new Bootstrap(APPLICATION_ENV);
return $app->ignition();

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

    /**
     * @param string $env
     */
    public function __construct($env = 'production')
    {
        $this->_app = new Silex\Application();

        $this->_loadEnvironment();

        $config = $this->_parseConfigFile($env);
        $this->_loadConfig($config);
    }


    /**
     * @return $this
     */
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

    protected function _parseConfigFile($env)
    {
        var_dump($this->_app['env']['commit.id']);die;

        $configKey = 'app:config';
        $configTtl = '10'; //seconds

        $redis = new \Redis();
        $redis->pconnect('127.0.0.1', 6379);
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);

        $config = $redis->get($configKey);
        if (!$config)
        {
            var_dump('CACHING!');

            $config = parse_ini_file('resources/config.ini', true);
            $config = $this->_mergeConfigs($config['common'], $config[$env]);

            $redis->setex($configKey, $configTtl, $config);
        }

        var_dump($config);die;

        return $config;
    }

    /**
     * @param $config
     */
    protected function _loadConfig($config)
    {
        $config['app.host'] = $config['app.default_protocol'] . $config['app.hostname'] . '/';

        $autoHost = isset($_SERVER['HTTP_HOST'])? $_SERVER['HTTP_HOST'] :$config['app.hostname'];

        $config['app.auto.host']            = $config['app.default_protocol'] . $autoHost . '/';
        $config['app.auto.hostname']        = $autoHost . '/';
        $config['app.auto.server_folder']   = dirname(__DIR__) . '/';

        $this->_app['config'] = $config;
        $this->_app['debug'] = !empty($this->_app['config']['debug.mode']) ? (bool) $this->_app['config']['debug.mode'] : false;

    }

    /**
     *
     */
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

    /**
     * Merge configs (Usually, common and environment config)
     *
     * @param $configA
     * @param $configB
     *
     * @return mixed
     */
    protected function _mergeConfigs($configA, $configB)
    {
        $merged = $configA;

        foreach ($configB as $key => &$value) {
            if (is_array($value) && isset ($merged[$key]) && is_array ($merged[$key])) {
                $merged[$key] = $this->_mergeConfigs($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

}

/**
 * Define application environment
 */
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

$app = new Bootstrap(APPLICATION_ENV);
return $app->ignition();

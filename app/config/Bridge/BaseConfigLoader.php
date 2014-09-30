<?php

namespace app\Config\Bridge;

use app\config\Interfaces\Loadable;

/**
 * Class BaseConfigLoader
 *
 * @package app\Config\Bridge
 */
class BaseConfigLoader implements Loadable
{

    protected $_app;

    protected $_namespace;

    protected $_env;

    public function __construct($app, $namespace)
    {
        $this->_app = $app;
        $this->_namespace = $namespace;
        $this->_env = $this->_app['env.name'];
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

    /**
     * @return string
     * @throws \Exception
     */
    protected function _getConfigFileRoute()
    {
        $route = dirname(dirname(dirname(__DIR__))) . '/src/' . str_replace('\\', '/' , $this->_namespace) . '/resources/config.ini';
        if (!file_exists($route)) {
            throw new \Exception('No config found for namespace ' . $this->_namespace);
        }

        return $route;
    }


    /**
     * @return array|bool|mixed|string
     */
    protected function _parseConfigFile()
    {
        $configKey = 'app:config';
        $configTtl = '10'; //seconds

        $redis = new \Redis();
        $redis->pconnect('127.0.0.1', 6379);
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);

        $config = $redis->get($configKey);
        if (!$config) {
            $config = parse_ini_file($this->_getConfigFileRoute(), true);
            $config = $this->_mergeConfigs($config['common'], $config[$this->_env]);

            $redis->setex($configKey, $configTtl, $config);
        }

        return $config;
    }

    /**
     * @param $config
     */
    protected function _loadConfig($config)
    {
        $config['app.host'] = $config['app.default_protocol'] . $config['app.hostname'];

        $autoHost = isset($_SERVER['HTTP_HOST'])? $_SERVER['HTTP_HOST'] :$config['app.hostname'];

        $config['app.auto.host']            = $config['app.default_protocol'] . $autoHost;
        $config['app.auto.hostname']        = $autoHost;
        $config['app.auto.server_folder']   = dirname(__DIR__);

        $this->_app['config'] = $config;
        $this->_app['debug'] = !empty($this->_app['config']['app.debug.mode']) ? (bool) $this->_app['config']['app.debug.mode'] : false;
    }

    public function load()
    {
        $config = $this->_parseConfigFile();
        $this->_loadConfig($config);
    }

}

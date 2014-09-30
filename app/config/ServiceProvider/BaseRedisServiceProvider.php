<?php

namespace app\Config\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use \Redis;

/**
 * Class BaseRedisProvider
 *
 * @package app\config\Provider
 */
class BaseRedisServiceProvider implements ServiceProviderInterface
{

    const SERVICE_NAME = 'redis';

    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 6379;

    /**
     * @param Application $app
     */
    public function boot(Application $app) {}

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app[self::SERVICE_NAME . '.object'] = $app->share(function () use ($app) {
            $connected = false;
            $redis = null;

            if (isset($app['config'][self::SERVICE_NAME . '.enabled']) &&
                $app['config'][self::SERVICE_NAME . '.enabled'])
            {
                $host = (isset($app[self::SERVICE_NAME . '.host']))? $app['config'][self::SERVICE_NAME . '.host'] : self::DEFAULT_HOST;
                $port = (isset($app[self::SERVICE_NAME . '.port']))? $app['config'][self::SERVICE_NAME .'.port'] : self::DEFAULT_PORT;

                $redis = new Redis();
                $connected = $redis->pconnect($host, $port);
            }

            if ($connected) {
                $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
            }

            return $redis;
        });
    }

}

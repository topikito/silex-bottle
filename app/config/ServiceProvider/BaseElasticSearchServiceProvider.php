<?php

namespace app\Config\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Elastica;;

/**
 * Class BaseElasticSearchServiceProvider
 *
 * @package app\Config\ServiceProvider
 */
class BaseElasticSearchServiceProvider implements ServiceProviderInterface
{

    const SERVICE_NAME = 'elastic_search';

    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 9200;

    public function boot(Application $app) {}

    public function register(Application $app)
    {
        $app[self::SERVICE_NAME . '.object'] = $app->share(function () use ($app) {
            $object = null;
            if (isset($app['config'][self::SERVICE_NAME . '.enabled']) &&
                $app['config'][self::SERVICE_NAME . '.enabled'])
            {
                $host = (isset($app[self::SERVICE_NAME . '.host']))? $app['config'][self::SERVICE_NAME . '.host'] : self::DEFAULT_HOST;
                $port = (isset($app[self::SERVICE_NAME . '.port']))? $app['config'][self::SERVICE_NAME .'.port'] : self::DEFAULT_PORT;

                $object = new Elastica\Client([
                    'host' => $host,
                    'port' => $port
                ]);
            }

            return $object;
        });
    }

}

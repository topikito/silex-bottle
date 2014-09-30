<?php

namespace app\Config\ServiceLoader;

use app\Config\Bridge\BaseServiceLoader;
use app\Config\ServiceProvider\BaseElasticSearchServiceProvider;
use Silex\Application;

/**
 * Class BaseElasticSearchServiceLoader
 *
 * @package app\Config\ServiceLoader
 */
class BaseElasticSearchServiceLoader extends BaseServiceLoader
{
    const SERVICE_NAME = 'elasticSearch';

    public function load()
    {
        $options = [
            self::SERVICE_NAME . '.enabled' => false
        ];

        $checkList = ['enabled', 'host', 'port'];
        foreach ($checkList as $value) {
            if (isset($app['config'][self::SERVICE_NAME . '.' . $value])) {
                $options[self::SERVICE_NAME . '.' . $value] = $app['config'][self::SERVICE_NAME . '.' . $value];
            }
        }

        $this->_app->register(new BaseElasticSearchServiceProvider(), $options);
    }

}

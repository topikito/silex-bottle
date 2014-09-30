<?php

namespace app\Config\ServiceLoader;

use app\Config\Bridge\BaseServiceLoader;
use app\Config\ServiceProvider\BaseRedisServiceProvider;
use Silex\Application;

/**
 * Class BaseRedisServiceLoader
 *
 * @package app\config\Service
 */
class BaseRedisServiceLoader extends BaseServiceLoader
{
    /**
     * @var string
     */
    protected $_serviceName = 'redis';

    public function load()
    {
        $options = [
            $this->_serviceName . '.enabled' => false
        ];

        $checkList = ['enabled', 'host', 'port'];
        foreach ($checkList as $value) {
            if (isset($app['config'][$this->_serviceName . '.' . $value])) {
                $options[$this->_serviceName . '.' . $value] = $app['config'][$this->_serviceName . '.' . $value];
            }
        }

        $this->_app->register(new BaseRedisServiceProvider(), $options);
    }

}

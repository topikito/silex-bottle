<?php

namespace app\Config\ServiceLoader;

use app\Config\Bridge\BaseServiceLoader;
use Silex\Provider\MonologServiceProvider;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class BaseMonologBaseServiceLoader
 *
 * @package app\config\Service
 */
class BaseMonologServiceLoader extends BaseServiceLoader
{

    public function load()
    {
        if (!empty($this->_app['config']['monolog.enabled'])) {

            $level = null;

            if (!empty($this->_app['config']['monolog.level'])) {
                $level = constant('\Symfony\Bridge\Monolog\Logger::' . $this->_app['config']['monolog.level']);
            }

            if (!$level) {
                $level = Logger::INFO;
            }

            $options = [
                'monolog.name' => 'bp',
                'monolog.logfile' => $this->_tempFolder .'monolog.log',
                'monolog.level' => $level
            ];

            $this->_app->register(new MonologServiceProvider(), $options);
        }
    }

}

<?php

namespace Topikito\Acme\Config\Service;

use app\config;
use Silex\Provider\MonologServiceProvider;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class Monolog
 *
 * @package app\config\Service
 */
class Monolog extends config\Bridges\Service
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

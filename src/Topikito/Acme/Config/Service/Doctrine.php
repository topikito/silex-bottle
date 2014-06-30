<?php

namespace Topikito\Acme\Config\Service;

use app\config;
use Silex\Provider\DoctrineServiceProvider;

/**
 * Class Doctrine
 *
 * @package app\config\Service
 */
class Doctrine extends config\Bridges\Service
{

    public function load()
    {
        $options = [
            'dbs.options' => [
                'mysql_read' => [
                    'driver' => 'pdo_mysql',
                    'host' => $this->_app['config']['database.host'],
                    'dbname' => $this->_app['config']['database.name'],
                    'user' => $this->_app['config']['database.user'],
                    'password' => $this->_app['config']['database.password'],
                    'charset' => 'utf8'
                ],
                'mysql_write' => [
                    'driver' => 'pdo_mysql',
                    'host' => $this->_app['config']['database.host'],
                    'dbname' => $this->_app['config']['database.name'],
                    'user' => $this->_app['config']['database.user'],
                    'password' => $this->_app['config']['database.password'],
                    'charset' => 'utf8'
                ]
            ]
        ];

        $this->_app->register(new DoctrineServiceProvider(), $options);
    }

}

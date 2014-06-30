<?php

namespace Topikito\Acme\Config\Service;

use app\config;
use Topikito\Acme\Provider\User;
use Silex\Provider\SecurityServiceProvider;

/**
 * Class Security
 *
 * @package app\config\Service
 */
class Security extends config\Bridges\Service
{

    public function load()
    {
        $options = [
            'security.firewalls' => [
                'default' => [
                    'pattern' => '^.*$',
                    'anonymous' => true,
                    'form' => [
                        'login_path' => '/login/',
                        'check_path' => 'login_check',
                        'failure_path' => 'login_failure'
                    ],
                    'logout' => ['logout_path' => '/logout/'],
                    'users' => $this->_app->share(function () {
                                return new User($this->_app['db']);
                            }),
                ],
            ],
            'security.access_rules' => [
                ['^/restricted/$', 'ROLE_USER'],
                ['^/messages/(.*)$', 'ROLE_USER'],
                ['^/invite/(.*)$', 'ROLE_USER'],
            ]
        ];

        $this->_app->register(new SecurityServiceProvider(), $options);
    }

}

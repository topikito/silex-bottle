<?php

namespace Topikito\Acme\Config\ServiceLoader;

use app\config;
use Topikito\Acme\Provider\SecurityUserProvider;
use Silex\Provider\SecurityServiceProvider;

/**
 * Class SecurityServiceLoader
 *
 * @package Topikito\Acme\Config\ServiceLoader
 */
class SecurityServiceLoader extends config\ServiceLoader\BaseSecurityServiceLoader
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
                                return new SecurityUserProvider($this->_app['db']);
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

<?php

namespace app\Config\ServiceLoader;

use app\Config\Bridge\BaseServiceLoader;
use Silex\Provider\SecurityServiceProvider;

/**
 * Class BaseSecurityBaseServiceLoader
 *
 * @package app\config\ServiceLoader
 */
class BaseSecurityServiceLoader extends BaseServiceLoader
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
                                //return new YourSecurityUserProvider($this->_app['db']);
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

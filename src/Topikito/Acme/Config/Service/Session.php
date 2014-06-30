<?php

namespace Topikito\Acme\Config\Service;

use app\config;
use Silex\Provider\SessionServiceProvider;

/**
 * Class Session
 *
 * @package app\config\Service
 */
class Session extends config\Bridges\Service
{

    public function load()
    {
        $lifeTime = time() + (60 * 60 * 24 * 30);  // A month

        $host = explode('.', $this->_app['config']['app.auto.hostname']);

        $tld = array_pop($host);

        if ($tld[strlen($tld) -1 ] == '/') {
            $tld = substr($tld, 0, strlen($tld) - 1);
        }

        $domainName = array_pop($host);
        $host = '.' . $domainName . '.' . $tld;

        session_set_cookie_params($lifeTime, '/' , $host);

        $options = [
            'session.storage.options' => [
                'cookie_lifetime' => $lifeTime
            ]
        ];

        $this->_app->register(new SessionServiceProvider(), $options);
    }

}

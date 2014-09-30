<?php

namespace app\Config\ServiceLoader;


use app\Config\Bridge\BaseServiceLoader;
use Silex\Provider\SessionServiceProvider;

/**
 * Class BaseSessionBaseServiceLoader
 *
 * @package app\config\Service
 */
class BaseSessionServiceLoader extends BaseServiceLoader
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

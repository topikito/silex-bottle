<?php

namespace Topikito\Acme\Config\Service;

use app\config;
use Silex\Provider\TwigServiceProvider;

/**
 * Class Twig
 *
 * @package Topikito\Acme\Config\Service
 */
class Twig extends config\Bridges\Service
{

    public function load()
    {
        $twigCache = !empty($this->_app['config']['twig.cache'])? (bool) $this->_app['config']['twig.cache'] : false;
        if ($twigCache) {
            $twigCache = $this->_tempFolder . 'cache/twig';
        }

        $options = [
            'twig.path' => $this->_sourceFolder,
            'twig.options' => [
                'strict_variables'  => false,
                'cache'             => $twigCache,
            ]
        ];

        $this->_app->register(new TwigServiceProvider(), $options);
    }

}

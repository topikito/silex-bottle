<?php

namespace Topikito\Acme\Config\ServiceLoader;

use app\config;
use Silex\Provider\TwigServiceProvider;

/**
 * Class TwigServiceLoader
 *
 * @package Topikito\Acme\Config\ServiceLoader
 */
class TwigServiceLoader extends config\ServiceLoader\BaseTwigServiceLoader
{

    public function load()
    {
        $twigCache = !empty($this->_app['config']['twig.cache'])? (bool) $this->_app['config']['twig.cache'] : false;
        if ($twigCache) {
            $twigCache = $this->_tempFolder . 'cache/twig';
        }

        $options = [
            'twig.path' => $this->_sourceFolder . 'Topikito/Acme/views/',
            'twig.options' => [
                'strict_variables'  => false,
                'cache'             => $twigCache,
            ]
        ];

        $this->_app->register(new TwigServiceProvider(), $options);
    }

}

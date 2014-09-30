<?php

namespace app\Config\ServiceLoader;

use app\Config\Bridge\BaseServiceLoader;
use Silex\Provider\TwigServiceProvider;

/**
 * Class BaseTwigServiceLoader
 *
 * @package app\config\Service
 */
class BaseTwigServiceLoader extends BaseServiceLoader
{

    public function load()
    {
        $twigCache = !empty($this->_app['config']['twig.cache'])? (bool) $this->_app['config']['twig.cache'] : false;
        if ($twigCache) {
            $twigCache = $this->_tempFolder . 'cache/twig';
        }

        $options = [
            'twig.path' => $this->_sourceFolder . 'path/to/your/views',
            'twig.options' => [
                'strict_variables'  => false,
                'cache'             => $twigCache,
            ]
        ];

        $this->_app->register(new TwigServiceProvider(), $options);
    }

}

<?php

namespace Topikito\Acme\Config\Service;

use app\config;
use Silex\Provider\TranslationServiceProvider;

/**
 * Class Translator
 *
 * @package app\config\Service
 */
class Translator extends config\Bridges\Service
{

    public function load()
    {
        $options = [
            'translator.messages' => []
        ];

        $this->_app->register(new TranslationServiceProvider(), $options);
    }

}

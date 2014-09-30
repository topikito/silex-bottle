<?php

namespace app\Config\ServiceLoader;

use app\Config\Bridge\BaseServiceLoader;
use Silex\Provider\TranslationServiceProvider;

/**
 * Class BaseTranslatorBaseServiceLoader
 *
 * @package app\config\Service
 */
class BaseTranslatorServiceLoader extends BaseServiceLoader
{

    public function load()
    {
        $options = [
            'translator.messages' => []
        ];

        $this->_app->register(new TranslationServiceProvider(), $options);
    }

}

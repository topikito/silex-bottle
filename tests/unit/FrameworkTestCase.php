<?php

namespace Tests\Unit;

require_once 'app/config/autoload.php';

use \Silex\WebTestCase;

class FrameworkTestCase extends WebTestCase {

    static $_app;

    public function createApplication() {
        if (is_null(self::$_app))  {
            self::$_app = require dirname(dirname(__DIR__)) . '/app/Config/Bootstrap.php';
            self::$_app['debug'] = true;
            self::$_app['exception_handler']->disable();
        }

        return self::$_app;
    }

}
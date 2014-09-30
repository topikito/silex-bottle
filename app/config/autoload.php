<?php

require_once 'Autoloader.php';
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$rootFolder = dirname(dirname(__DIR__));
$srcLoader = new \App\config\Autoloader($rootFolder);

spl_autoload_register(function ($className) use ($srcLoader) {
    return $srcLoader->load($className);
});

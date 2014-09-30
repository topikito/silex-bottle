<?php

/**
 * This code uses Silex and was inspired by "Symfony 2", "Codazo" and "SiCMS" by Roberto Nygaard (@topikito).
 */
$app  = require_once __DIR__ . '/../../../../app/Config/Bootstrap.php';

if ($app['config']['app.cache.enabled'] == true) {
    $app['http_cache']->run();
} else {
    $app->run();
}
<?php

// define application root for better file path definitions
define('APPLICATION_ROOT', realpath(__DIR__ . '.'));

// define application environment, needs to be set within virtual host
// but could be chosen by any other identifier
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// setup autoloading from composer
require_once 'vendor/autoload.php';

// change working dir
chdir(dirname(__DIR__));

// get configuration file based on application environment
switch (APPLICATION_ENV) {
    case 'production':
        $configFile = APPLICATION_ROOT . '/config/production.config.php';
        break;
    case 'development':
    default:
        $configFile = APPLICATION_ROOT . '/config/development.config.php';
        break;
}

// run the application
Zend\Mvc\Application::init(include $configFile)->run();

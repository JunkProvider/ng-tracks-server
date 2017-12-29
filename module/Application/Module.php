<?php

namespace Application;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Application module class.
 *
 * @package Application
 */
class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    InitProviderInterface
{
    /**
     * Initialize module on loading.
     *
     * @param ModuleManagerInterface $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!defined('APPLICATION_MODULE_ROOT')) {
            define('APPLICATION_MODULE_ROOT', realpath(__DIR__));
        }
    }

    /**
     * Listen to the bootstrap event.
     *
     * @param EventInterface $e
     *
     * @return void
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // Enable error reporting.
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
		
		// Set default timzone
		date_default_timezone_set('utc');
		
		// Start the session.
		session_start();
        
        // configure session
        // $e->getApplication()->getServiceManager()->get('Session\Config');
    }

    /**
     * Returns configuration to merge with application configuration.
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __NAMESPACE__ => __DIR__ . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
}

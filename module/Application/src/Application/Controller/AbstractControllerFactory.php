<?php

namespace Application\Controller;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AbstractControllerFactory implements AbstractFactoryInterface
{
	/**
	 * @see AbstractFactoryInterface::canCreate()
	 */
	public function canCreate(ContainerInterface $container, $requestedName)
	{
		return true;
	}
	
	/**
	 * @see FactoryInterface::__invoke()
	 */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
    	$className = '\\' . $requestedName . 'Controller';
    	return new $className($container);
    }
}

<?php

namespace Application\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;

class SchemaToolFactory implements FactoryInterface
{
	/**
	 * @see FactoryInterface::__invoke()
	 */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
    	$entityManager = $container->get(EntityManager::class);
    	return new SchemaTool($entityManager);
    }
}

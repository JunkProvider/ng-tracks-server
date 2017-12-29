<?php

namespace Application\Model;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;

class LinkRepositoryFactory implements FactoryInterface
{
	/**
	 * @see FactoryInterface::__invoke()
	 */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
    	/* @var EntityManager $entityManager */
    	$entityManager = $container->get(EntityManager::class);
    	$doctrineRepository = $entityManager->getRepository(Link::class);
    	return new LinkRepository($entityManager, $doctrineRepository);
    }
}

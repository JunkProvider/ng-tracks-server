<?php

namespace Application\Model;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;

class GenreRepositoryFactory implements FactoryInterface
{
	/**
	 * @see FactoryInterface::__invoke()
	 */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
    	/* @var EntityManager $entityManager */
    	$entityManager = $container->get(EntityManager::class);
    	$doctrineRepository = $entityManager->getRepository(Genre::class);
    	return new GenreRepository($entityManager, $doctrineRepository);
    }
}

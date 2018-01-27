<?php
namespace Application\Model;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;

class TrackRepositoryFactory implements FactoryInterface
{

	/**
	 *
	 * @see FactoryInterface::__invoke()
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		/* @var EntityManager $entityManager */
		$entityManager = $container->get(EntityManager::class);
		$doctrineRepository = $entityManager->getRepository(Track::class);
		return new TrackRepository($entityManager, $doctrineRepository);
	}
}

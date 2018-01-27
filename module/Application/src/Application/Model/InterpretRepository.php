<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class InterpretRepository
{

	/**
	 */
	private $entityManager;

	/**
	 *
	 * @var EntityRepository
	 */
	private $doctrineRepository;

	/**
	 *
	 * @param EntityManager $entityManager        	
	 * @param EntityRepository $doctrineRepository        	
	 */
	public function __construct(EntityManager $entityManager, EntityRepository $doctrineRepository)
	{
		$this->entityManager = $entityManager;
		$this->doctrineRepository = $doctrineRepository;
	}

	/**
	 *
	 * @param array $orderBy        	
	 *
	 * @return Interpret[]
	 */
	public function findAll($orderBy = [ 'title' => 'asc' ])
	{
		return $this->doctrineRepository->findBy([], $orderBy);
	}

	/**
	 *
	 * @param int $id        	
	 *
	 * @return Interpret
	 */
	public function tryGetOneById($id)
	{
		return $this->doctrineRepository->find($id);
	}

	/**
	 *
	 * @param string $name        	
	 *
	 * @return Interpret|null
	 */
	public function tryGetOneByName($name)
	{
		return $this->doctrineRepository->findOneBy([
			'name' => $name
		]);
	}

	/**
	 *
	 * @param Interpret $interpret        	
	 */
	public function add(Interpret $interpret)
	{
		$this->entityManager->persist($interpret);
	}

	/**
	 *
	 * @param Interpret $interpret        	
	 */
	public function remove(Interpret $interpret)
	{
		$this->entityManager->remove($interpret);
	}
}

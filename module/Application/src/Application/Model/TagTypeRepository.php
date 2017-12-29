<?php

namespace Application\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class TagTypeRepository
{
	/**
	 */
	private $entityManager;
	
	/**
	 * @var EntityRepository
	 */
	private $doctrineRepository;
	
	/**
	 * @param EntityManager    $entityManager
	 * @param EntityRepository $doctrineRepository
	 */
	public function __construct(EntityManager $entityManager, EntityRepository $doctrineRepository)
	{
		$this->entityManager = $entityManager;
		$this->doctrineRepository = $doctrineRepository;
	}
	
	/**
	 * @param array $orderBy
	 *
	 * @return TagType[]
	 */
	public function findAll($orderBy = [ 'name' => 'asc' ])
	{
		return $this->doctrineRepository->findBy([], $orderBy);
	}
	
	/**
	 * @param int $id
	 *
	 * @return TagType
	 */
	public function tryGetOneById($id)
	{
		return $this->doctrineRepository->find($id);
	}
	
	/**
	 * @param string $name
	 *
	 * @return TagType|null
	 */
	public function tryGetOneByName($name)
	{
		return $this->doctrineRepository->findOneBy([ 'name' => $name ]);
	}
	
	/**
	 * @param TagType $tagType
	 */
	public function add(TagType $tagType)
	{
		$this->entityManager->persist($tagType);
	}
	
	/**
	 * @param TagType $tagType
	 */
	public function remove(TagType $tagType)
	{
		$this->entityManager->remove($tagType);
	}
}

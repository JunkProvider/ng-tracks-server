<?php

namespace Application\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class TagRepository
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
	 * @return Tag[]
	 */
	public function findAll($orderBy = [ 'name' => 'asc' ])
	{
		return $this->doctrineRepository->findBy([], $orderBy);
	}
	
	/**
	 * @param int $id
	 *
	 * @return Tag
	 */
	public function tryGetOneById($id)
	{
		return $this->doctrineRepository->find($id);
	}
	
	/**
	 * @param string $name
	 *
	 * @return Tag|null
	 */
	public function tryGetOneByName($name)
	{
		return $this->doctrineRepository->findOneBy([ 'name' => $name ]);
	}
	
	/**
	 * @param Tag $tag
	 */
	public function add(Tag $tag)
	{
		$this->entityManager->persist($tag);
	}
	
	/**
	 * @param Tag $tag
	 */
	public function remove(Tag $tag)
	{
		$this->entityManager->remove($tag);
	}
}

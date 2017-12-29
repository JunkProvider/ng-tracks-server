<?php

namespace Application\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class LinkRepository
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
	 * @return Link[]
	 */
	public function findAll($orderBy = [ 'url' => 'asc' ])
	{
		return $this->doctrineRepository->findBy([], $orderBy);
	}
	
	/**
	 * @param int $id
	 *
	 * @return Link
	 */
	public function tryGetOneById($id)
	{
		return $this->doctrineRepository->find($id);
	}
	
	/**
	 * @param string $url
	 *
	 * @return Link|null
	 */
	public function tryGetOneByUrl($url)
	{
		return $this->doctrineRepository->findOneBy([ 'url' => $url ]);
	}
	
	/**
	 * @param Link $link
	 */
	public function add(Link $link)
	{
		$this->entityManager->persist($link);
	}
	
	/**
	 * @param Link $link
	 */
	public function remove(Link $link)
	{
		$this->entityManager->remove($link);
	}
}

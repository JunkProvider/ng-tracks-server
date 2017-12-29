<?php

namespace Application\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class TrackRepository
{
	/**
	 * @var EntityManager
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
	 * @return Track[]
	 */
	public function findAll($orderBy = [ 'title' => 'asc' ])
	{
		return $this->doctrineRepository->findBy([], $orderBy);
	}
	
	/**
	 * @param int $id
	 *
	 * @return Track
	 */
	public function getOneById($id)
	{
		$track = $this->doctrineRepository->find($id);
		if ($track === null) {
			throw new \RuntimeException('Can not get track with id ' . $id . '. Track does not exist.');
		}
		return $track;
	}
	
	/**
	 * @param int $id
	 *
	 * @return Track|null
	 */
	public function tryGetOneById($id)
	{
		return $this->doctrineRepository->find($id);
	}

	/**
	 * @param string            $searchText
	 * @param FilterInterface[] $filters
	 * @param array             $orderBy
	 * 
	 * @throws \Exception
	 * 
	 * @return Track[]
	 */
	public function getBySearchTextAndFilters($searchText, array $filters, $orderBy = [ 'title' => 'asc' ])
	{
		$qb = $this->createQueryBuilder('t');
		$expr = $qb->expr();
	
		foreach ($filters as $filter) {
			$filter->apply($qb);
		}
		
		foreach ($orderBy as $prop => $direction) {
			$qb->addOrderBy('t.' . $prop, $direction);
		}

		return $qb->getQuery()->setMaxResults(1000)->getResult();
	}
	
	/**
	 * @param Track $track
	 */
	public function add(Track $track)
	{
		$this->entityManager->persist($track);
	}
	
	/**
	 * @param Track $track
	 */
	public function remove(Track $track)
	{
		$this->entityManager->remove($track);
	}
	
	/**
	 * @param string $alias
	 * @param string $indexBy The index for the from.
	 *
	 * @return QueryBuilder
	 */
	private function createQueryBuilder($alias, $indexBy = null)
	{
		$doctrineQb = $this->doctrineRepository->createQueryBuilder($alias, $indexBy);
		$qb = QueryBuilder::extend($doctrineQb);
		$qb->select($alias)->from($doctrineQb->getRootEntities()[0], $alias, $indexBy);
		return $qb;
	}
}

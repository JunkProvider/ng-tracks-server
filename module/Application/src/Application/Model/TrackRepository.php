<?php

namespace Application\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TrackRepository
{
	private static function mapSortCriterion($sortCriterion)
	{
		switch (strtoupper($sortCriterion)) {
			case 'TITLE':
				return 'title';
			case 'RATING':
				return 'rating';
			default:
				throw new \InvalidArgumentException('Unknown sort criterion "' . $sortCriterion . '".');
		}
	}
	
	private static function mapSortDirection($sortDirection)
	{
		switch (strtoupper($sortDirection)) {
			case 'ASC':
				return 'asc';
			case 'DESC':
				return 'desc';
			default:
				throw new \InvalidArgumentException('Unknown sort direction "' . $sortDirection . '".');
		}
	}
	
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
	public function getBySearchTextAndFilters($searchText, array $filters, $sortCriterion = 'TITLE', $sortDirection = 'ASC', $offset = 0, $limit = null)
	{
		$qb = $this->createQueryBuilder('track');
		$expr = $qb->expr();
		
		if ($searchText) {
			$qb->andWhere($expr->like('track.title', '\'%' . $searchText . '%\''));
			/*$qb->join('t.interprets', 'interpret');
			$qb->andWhere(
				$expr->orX(
					$expr->like('t.title', '\'%' . $searchText . '%\''),
					$expr->like('interpret.name', '\'%' . $searchText . '%\'')
				)	
			);*/
		}
	
		foreach ($filters as $filter) {
			$filter->apply($qb);
		}
		
		$qb->orderBy('track.' . TrackRepository::mapSortCriterion($sortCriterion), TrackRepository::mapSortDirection($sortDirection));
		
		$query = $qb->getQuery();
		
		$paginator = new Paginator($query, $fetchJoinCollection = true);
		$totalCount = $paginator->count();
		
		if ($offset !== null && $offset > 0) {
			$query->setFirstResult($offset);
		}
		
		if ($limit !== null) {
			$query->setMaxResults($limit);
		}
		
		return new PagedQueryResult($query->getResult(), $totalCount);
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

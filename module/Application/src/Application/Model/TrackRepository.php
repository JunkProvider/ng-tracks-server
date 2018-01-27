<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\AbstractQuery;

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
	 *
	 * @var EntityManager
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
	 * @return Track[]
	 */
	public function findAll($orderBy = [ 'title' => 'asc' ])
	{
		return $this->doctrineRepository->findBy([], $orderBy);
	}

	/**
	 *
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
	 *
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
	 * @param FilterInterface   $sorting
	 * @param int               $pageIndex
	 * @param int               $pageSize
	 * 
	 * @return PagedQueryResult
	 */
	public function getFiltered($searchText, array $filters, FilterInterface $sorting, $pageIndex, $pageSize)
	{
		$resultPreview = $this->getIdsFiltered($searchText, $filters, $sorting, $pageIndex, $pageSize);
		
		$queryBuilder = $this->createQueryBuilder('track');
		$expr = $queryBuilder->expr();
		
		$queryBuilder
			->select('track')
			->from(Track::class, 'track')
			->addSelect('interpret')
			->leftJoin('track.interprets', 'interpret')
			->addSelect('genre')
			->leftJoin('track.genres', 'genre')
			->addSelect('tag')
			->leftJoin('track.tags', 'tag')
			->addSelect('tagType')
			->leftJoin('tag.type', 'tagType')
			->where($expr->in('track.id', $resultPreview->getItems()));
		
		$query = $queryBuilder->getQuery();

		return new PagedQueryResult($query->getResult(), $resultPreview->getPageIndex(), $resultPreview->getTotalCount());
	}
	
	/**
	 * @param string            $searchText
	 * @param FilterInterface[] $filters
	 * @param FilterInterface   $sorting
	 * @param int               $pageIndex
	 * @param int               $pageSize
	 *
	 * @return PagedQueryResult
	 */
	public function getIdsFiltered($searchText, array $filters, FilterInterface $sorting, $pageIndex, $pageSize)
	{
		$queryBuilder = $this->createQueryBuilder('track');
		$expr = $queryBuilder->expr();
		
		
		// Select From
		$queryBuilder
			->select('track.id')
			->distinct()
			->from(Track::class, 'track');
		
		// Where
		if ($searchText) {
			$queryBuilder
				->leftJoin('track.interprets', 'interpret')
				->leftJoin('track.genres', 'genre')
				->leftJoin('track.tags', 'tag')
				->leftJoin('tag.type', 'tagType')
				->andWhere(
					$expr->orX(
						$expr->like('track.title', '\'%' . $searchText . '%\''),
						$expr->like('interpret.name', '\'%' . $searchText . '%\''),
						$expr->like('genre.name', '\'%' . $searchText . '%\''),
						$expr->like('tagType.name', '\'%' . $searchText . '%\'')
					)
				);
		}
		foreach ($filters as $filter) {
			$filter->apply($queryBuilder);
		}
		
		// Order By
		$sorting->apply($queryBuilder);
		
		
		$query = $queryBuilder->getQuery();
		
		$results = $query->getResult();
		$totalCount = count($results);
		
		if ($pageIndex * $pageSize >= $totalCount) {
			$pageIndex = ceil($totalCount / $pageSize);
		}
		
		$iStart = $pageIndex * $pageSize;
		$iEnd = min([ ($pageIndex + 1) * $pageSize, $totalCount ]);
		$ids = [];
		for ($i = $iStart; $i < $iEnd; $i++) {
			$ids[] = $results[$i]['id'];
		}
		
		return new PagedQueryResult($ids, $pageIndex, $totalCount);
	}

	/**
	 *
	 * @param Track $track        	
	 */
	public function add(Track $track)
	{
		$this->entityManager->persist($track);
	}

	/**
	 *
	 * @param Track $track        	
	 */
	public function remove(Track $track)
	{
		$this->entityManager->remove($track);
	}

	/**
	 *
	 * @param string $alias        	
	 * @param string $indexBy
	 *        	The index for the from.
	 *        	
	 * @return QueryBuilder
	 */
	private function createQueryBuilder($alias, $indexBy = null)
	{
		$doctrineQb = $this->doctrineRepository->createQueryBuilder($alias)->getEntityManager()->createQueryBuilder();
		//$this->doctrineRepository->createQueryBuilder($alias, $indexBy);
		$qb = QueryBuilder::extend($doctrineQb);
		// $qb->select($alias)->from($doctrineQb->getRootEntities()[0], $alias, $indexBy);
		return $qb;
	}
}

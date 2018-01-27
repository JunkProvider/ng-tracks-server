<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class GenreRepository
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
	 * @return Genre[]
	 */
	public function findAll($orderBy = [ 'title' => 'asc' ])
	{
		return $this->doctrineRepository->findBy([], $orderBy);
	}

	/**
	 *
	 * @param int $id        	
	 *
	 * @return Genre
	 */
	public function tryGetOneById($id)
	{
		return $this->doctrineRepository->find($id);
	}

	/**
	 *
	 * @param string $name        	
	 *
	 * @return Genre|null
	 */
	public function tryGetOneByName($name)
	{
		return $this->doctrineRepository->findOneBy([
			'name' => $name
		]);
	}

	/**
	 *
	 * @param Genre $genre        	
	 */
	public function add(Genre $genre)
	{
		$this->entityManager->persist($genre);
	}

	/**
	 *
	 * @param Genre $genre        	
	 */
	public function remove(Genre $genre)
	{
		$this->entityManager->remove($genre);
	}
}

<?php
namespace Application\Model\TrackSort;

use Application\Model\FilterInterface;
use Application\Model\QueryBuilder;

class RatingSort implements FilterInterface
{

	/**
	 *
	 * @var string
	 */
	private $direction;

	/**
	 *
	 * @param string $direction        	
	 */
	public function __construct($direction)
	{
		$this->direction = $direction;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 */
	public function apply(QueryBuilder $qb)
	{
		$qb->addOrderBy('track.rating', $this->direction);
		$qb->addOrderBy('track.title', 'asc');
	}
}

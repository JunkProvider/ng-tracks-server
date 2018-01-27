<?php
namespace Application\Model\TrackSort;

use Application\Model\QueryEffectInterface;
use Application\Model\QueryBuilder;

class TitleSort implements QueryEffectInterface
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
		$qb->orderBy('track.title', $this->direction);
	}
}

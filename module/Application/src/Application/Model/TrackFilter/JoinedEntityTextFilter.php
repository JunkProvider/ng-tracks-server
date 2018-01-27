<?php
namespace Application\Model\TrackFilter;

use Application\Model\QueryEffectInterface;
use Application\Model\QueryBuilder;

class JoinedEntityTextFilter implements QueryEffectInterface
{

	/**
	 *
	 * @var string
	 */
	private $join;

	/**
	 *
	 * @var string
	 */
	private $alias;

	/**
	 *
	 * @var string
	 */
	private $operator;

	/**
	 *
	 * @var string[]
	 */
	private $values;

	/**
	 *
	 * @param string $join        	
	 * @param string $alias        	
	 * @param string $operator        	
	 * @param string[] $values        	
	 */
	public function __construct($join, $alias, $operator, array $values)
	{
		$this->join = $join;
		$this->alias = $alias;
		$this->operator = $operator;
		$this->values = $values;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 */
	public function apply(QueryBuilder $qb)
	{
		if (count($this->values) == 0) {
			return;
		}
		
		$qb->leftJoin('track.' . $this->join, $this->alias);
		
		$expr = $qb->expr();
		
		$likeXs = [];
		foreach ($this->values as $value) {
			$value = trim($value);
			$likeXs[] = $expr->like($this->alias . '.name', '\'%' . $value . '%\'');
		}
		$orX = call_user_func_array([
			$expr,
			'orX'
		], $likeXs);
		$qb->andWhere($orX);
	}
}

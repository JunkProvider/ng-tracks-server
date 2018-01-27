<?php
namespace Application\Model\TrackFilter;

use Application\Model\FilterInterface;
use Application\Model\QueryBuilder;

class NumberFilter implements FilterInterface
{

	/**
	 *
	 * @var string
	 */
	private $field;

	/**
	 *
	 * @var string
	 */
	private $operator;

	/**
	 *
	 * @var float
	 */
	private $value;

	/**
	 *
	 * @param string $field        	
	 * @param string $operator        	
	 * @param float $values        	
	 */
	public function __construct($field, $operator, $value)
	{
		$this->field = $field;
		$this->operator = $operator;
		$this->value = $value;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 */
	public function apply(QueryBuilder $qb)
	{
		$qb->andWhere(call_user_func_array([
			$qb->expr(),
			$this->operator
		], [
			'track.' . $this->field,
			$this->value
		]));
	}
}

<?php
namespace Application\Model;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class QueryBuilder extends DoctrineQueryBuilder
{

	/**
	 *
	 * @var array
	 */
	private $joins = [];

	/**
	 *
	 * @param DoctrineQueryBuilder $qb        	
	 */
	public static function extend(DoctrineQueryBuilder $qb)
	{
		return new QueryBuilder($qb->getEntityManager());
	}

	/**
	 *
	 * @param EntityManagerInterface $em        	
	 */
	public function __construct(EntityManagerInterface $em)
	{
		parent::__construct($em);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 */
	public function innerJoin($join, $alias, $conditionType = null, $condition = null, $indexBy = null)
	{
		if ($this->registerJoin('inner', func_get_args())) {
			return parent::innerJoin($join, $alias, $conditionType, $condition, $indexBy);
		}
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 */
	public function leftJoin($join, $alias, $conditionType = null, $condition = null, $indexBy = null)
	{
		if ($this->registerJoin('left', func_get_args())) {
			return parent::leftJoin($join, $alias, $conditionType, $condition, $indexBy);
		}
	}

	/**
	 *
	 * @param string $type        	
	 * @param array $args        	
	 *
	 * @return bool
	 */
	private function registerJoin($type, array $args)
	{
		array_unshift($args, 'inner');
		if ($this->containsJoin($args)) {
			return false;
		}
		$this->joins[] = $args;
		return true;
	}

	/**
	 *
	 * @param array $args        	
	 *
	 * @return bool
	 */
	private function containsJoin(array $args)
	{
		foreach ($this->joins as $join) {
			if ($this->joinEquals($args, $join)) {
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 * @param array $args        	
	 * @param array $join        	
	 *
	 * @return bool
	 */
	private function joinEquals(array $args, array $join)
	{
		if (count($args) != count($join)) {
			return false;
		}
		foreach ($join as $argIndex => $arg) {
			if ($arg != $args[$argIndex]) {
				return false;
			}
		}
		return true;
	}
}

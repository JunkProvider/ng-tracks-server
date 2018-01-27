<?php
namespace Application\Model\TrackFilter;

use Application\Model\QueryEffectInterface;
use Application\Model\QueryBuilder;

class TagsFilter implements QueryEffectInterface
{

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
	 * @param string $operator        	
	 * @param array $values        	
	 */
	public function __construct($operator, array $values)
	{
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
		
		$qb->leftJoin('track.tags', 'tag');
		$qb->leftJoin('tag.type', 'tagType');
		
		$expr = $qb->expr();
		
		$andXs = [];
		foreach ($this->values as $item) {
			$name = trim($item['name']);
			$value = $item['value'];
			$andXs[] = $expr->andX($expr->like('tagType.name', '\'%' . $name . '%\''), $expr->gte('tag.value', $value));
		}
		$orX = call_user_func_array([
			$expr,
			'orX'
		], $andXs);
		$qb->andWhere($orX);
	}
}

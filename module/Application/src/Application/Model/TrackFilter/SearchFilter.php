<?php
namespace Application\Model\TrackFilter;

use Application\Model\QueryEffectInterface;
use Application\Model\QueryBuilder;

class SearchFilter implements QueryEffectInterface
{

	/**
	 *
	 * @var string
	 */
	private $text;

	/**
	 *
	 * @param string $text        	
	 */
	public function __construct($text)
	{
		$this->text = trim($text);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 */
	public function apply(QueryBuilder $queryBuilder)
	{
		$text = $this->text;
		
		if ($text === null || $text === '') {
			return;
		}
		
		$expr = $queryBuilder->expr();
		
		
		$queryBuilder
			->leftJoin('track.interprets', 'interpret')
			->leftJoin('track.genres', 'genre')
			->leftJoin('track.tags', 'tag')
			->leftJoin('tag.type', 'tagType')
			->andWhere(
				$expr->orX(
					$expr->like('track.title', '\'%' . $text . '%\''),
					$expr->like('interpret.name', '\'%' . $text . '%\''),
					$expr->like('genre.name', '\'%' . $text . '%\''),
					$expr->like('tagType.name', '\'%' . $text . '%\'')
				)
			);
	}
}

<?php
namespace Application\Model;

interface QueryEffectInterface
{

	public function apply(QueryBuilder $qb);
}

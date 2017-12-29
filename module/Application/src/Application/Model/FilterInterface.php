<?php

namespace Application\Model;

interface FilterInterface
{
	public function apply(QueryBuilder $qb);
}

<?php

namespace Application\Model;

class PagedQueryResult implements \JsonSerializable
{

	/**
	 * @var array
	 */
	private $items;
	
	/**
	 * @var int
	 */
	private $pageIndex;

	/**
	 * @var int
	 */
	private $totalCount;

	/**
	 * @param array $items      
	 * @param int   $pageIndex  	
	 * @param int   $totalCount        	
	 */
	public function __construct(array $items, $pageIndex, $totalCount)
	{
		$this->items = $items;
		$this->pageIndex = $pageIndex;
		$this->totalCount = $totalCount;
	}

	/**
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}
	
	/**
	 * @return int
	 */
	public function getPageIndex()
	{
		return $this->pageIndex;
	}

	/**
	 * @return int
	 */
	public function getTotalCount()
	{
		return $this->totalCount;
	}

	/**
	 * {@inheritDoc}
	 */
	public function jsonSerialize()
	{
		return [
			'items' => $this->getItems(),
			'pageIndex' => $this->getPageIndex(),
			'totalCount' => $this->getTotalCount()
		];
	}
}
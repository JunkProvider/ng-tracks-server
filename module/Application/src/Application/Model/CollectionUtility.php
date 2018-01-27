<?php
namespace Application\Model;

use Doctrine\Common\Collections\Collection;

class CollectionUtility
{

	public static function update(Collection $items, array $newItems)
	{
		$itemsToAdd = [];
		$itemsToAddWithNoId = [];
		foreach ($newItems as $item) {
			if ($item->id === null) {
				$itemsToAddWithNoId[] = $item;
			} else {
				$itemsToAdd[$item->id] = $item;
			}
		}
		
		foreach ($items as $index => $item) {
			if (isset($itemsToAdd[$item->id])) {
				unset($itemsToAdd[$item->id]);
			} else {
				$items->remove($index);
			}
		}
		
		foreach ($itemsToAdd as $item) {
			$items->add($item);
		}
		
		foreach ($itemsToAddWithNoId as $item) {
			$items->add($item);
		}
	}
}

<?php

namespace Application\Controller;


use Application\Model\TagTypeRepository;

class TagTypeController extends AbstractController
{
	public function indexAction()
	{
		try {
			return $this->jsonResponse($this->getTagTypeRepository()->findAll([ 'name' => 'asc' ]));
		} catch (\Exception $e) {
			return $this->jsonResponseFromException($e);
		}
	}
	
	/**
	 * @return TagTypeRepository
	 */
	private function getTagTypeRepository()
	{
		return $this->getContainer()->get(TagTypeRepository::class);
	}
}

<?php

namespace Application\Controller;

use Application\Model\InterpretRepository;

class InterpretController extends AbstractController
{
	public function indexAction()
	{
		try {
			return $this->jsonResponse($this->getInterpretRepository()->findAll([ 'name' => 'asc' ]));
		} catch (\Exception $e) {
			return $this->jsonResponseFromException($e);
		}
	}
	
	/**
	 * @return InterpretRepository
	 */
	private function getInterpretRepository()
	{
		return $this->getContainer()->get(InterpretRepository::class);
	}
}

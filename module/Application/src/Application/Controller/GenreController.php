<?php

namespace Application\Controller;

use Application\Model\GenreRepository;

class GenreController extends AbstractController
{
	public function indexAction()
	{
		try {
			return $this->jsonResponse($this->getGenreRepository()->findAll([ 'name' => 'asc' ]));
		} catch (\Exception $e) {
			return $this->jsonResponseFromException($e);
		}
	}
	
	/**
	 * @return GenreRepository
	 */
	private function getGenreRepository()
	{
		return $this->getContainer()->get(GenreRepository::class);
	}
}

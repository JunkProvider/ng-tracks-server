<?php

namespace Application\Controller;

use Application\Model\TrackRepository;
use Application\Model\Track;
use Application\Model\InterpretRepository;
use Application\Model\Interpret;
use Application\Model\Genre;
use Application\Model\GenreRepository;
use Application\Model\LinkRepository;
use Application\Model\Link;
use Application\Model\TrackFilter\NumberFilter;
use Application\Model\Tag;
use Application\Model\TagTypeRepository;
use Application\Model\TagType;
use Application\Model\TrackFilter\TagsFilter;
use Application\Model\TrackFilter\JoinedEntityTextFilter;
use Application\Model\TrackSort\TitleSort;
use Application\Model\TrackSort\RatingSort;
use Application\Model\TrackSort\InterpretSort;

class TrackController extends AbstractController
{
	public function indexAction()
	{
		try {
			$serializedFilters = json_decode($this->params()->fromQuery('filters', '[]'), true);
			$filters = [];
			
			foreach ($serializedFilters as $serializedFilter) {
				switch ($serializedFilter['type']) {
					case 'interprets':
						$filters[] = new JoinedEntityTextFilter('interprets', 'interpret', $serializedFilter['operator'], explode(',', $serializedFilter['value']));
						break;
					case 'genres':
						$filters[] = new JoinedEntityTextFilter('genres', 'genre', $serializedFilter['operator'], explode(',', $serializedFilter['value']));
						break;
					case 'rating':
						$filters[] = new NumberFilter($serializedFilter['type'], $serializedFilter['operator'], (float)$serializedFilter['value']);
						break;
					case 'tags':
						$items = [];
						foreach (explode(',', $serializedFilter['value']) as $tag) {
							$tag = trim($tag);
							if (!$tag) {
								continue;
							}
							$name = $tag;
							$value = 1;
							$valueIdx = strpos($tag, ':');
							if ($valueIdx) {
								$name = substr($tag, 0, $valueIdx);
								$valueStr = substr($tag, $valueIdx + 1);
								$value = (int) $valueStr;
							}
							$items[] = [ 'name' => $name, 'value' => $value ];
						}
						$filters[] = new TagsFilter($serializedFilter['operator'], $items);
						break;
					default:
						throw new \Exception('Unknown filter type "' . $serializedFilter['type'] . '".');
				}
			}
			
			$search = $this->params()->fromQuery('search', '');
			$sortCriterion = $this->params()->fromQuery('sortCriterion', 'TITLE');
			$sortDirection = $this->params()->fromQuery('sortDirection', 'ASC');
			
			$sorting = null;
			switch (strtoupper($sortCriterion)) {
				case 'TITLE':
					$sorting = new TitleSort($sortDirection);
					break;
				case 'RATING':
					$sorting = new RatingSort($sortDirection);
					break;
				case 'INTERPRET':
					$sorting = new InterpretSort($sortDirection);
					break;
			}
			
			$offset = (int)$this->params()->fromQuery('offset', 0);
			$limit = (int)$this->params()->fromQuery('limit', 1000);
			
			$result = $this->getTrackRepository()->getBySearchTextAndFilters($search, $filters, $sorting, $offset, $limit);
			
			return $this->jsonResponse($result);
		} catch (\Exception $e) {
			return $this->jsonResponseFromException($e);
		}
	}
	
	public function saveAction()
	{
		try {
			// Get dependencies
			
			$entityManager = $this->getEntityManager();
			$trackRepository = $this->getTrackRepository();
			$interpretRepository = $this->getInterpretRepository();
			$genreRepository = $this->getGenreRepository();
			$tagTypeRepository = $this->getTagTypeRepository();
			$linkRepository = $this->getLinkRepository();
			
			// Get parameters
			
			$trackData = $this->getJsonPost();
			
			// Update track

			$track = null;
			
			if (isset($trackData['id'])) {
				$track = $trackRepository->getOneById($trackData['id']);
			} else {
				$track = new Track();
			}
			
			$track->title = trim($trackData['title']);
			$track->rating = isset($trackData['rating']) ? (int)$trackData['rating'] : null;
	
			$track->setInterprets(array_map(function($interpretName) use ($interpretRepository) {
				$interpretName = trim($interpretName);
				$interpret = $interpretRepository->tryGetOneByName($interpretName);
				if ($interpret === null) {
					$interpret = new Interpret();
					$interpret->name = $interpretName;
				}
				return $interpret;
			}, isset($trackData['interprets']) ? $trackData['interprets'] : []));
			
			$track->setGenres(array_map(function($genreName) use ($genreRepository) {
				$genreName = trim($genreName);
				$genre = $genreRepository->tryGetOneByName($genreName);
				if ($genre === null) {
					$genre = new Genre();
					$genre->name = $genreName;
				}
				return $genre;
			}, isset($trackData['genres']) ? $trackData['genres'] : []));
			
			$tagData = isset($trackData['tags']) ? $trackData['tags'] : [];
			$tags = [];
			foreach ($tagData as $tagName => $tagValue) {
				$tagName = trim($tagName);
				$tagValue = (int)$tagValue;
				if ($tagValue <= 0) {
					continue;
				}
				$tagType = $tagTypeRepository->tryGetOneByName($tagName);
				if ($tagType === null) {
					$tagType = new TagType($tagName);
				}
				$tag = new Tag($track, $tagType);
				$tag->setValue($tagValue);
				$tags[] = $tag;
			}
			$track->setTags($tags);

			$track->setLinks(array_map(function($linkUrl) use ($linkRepository) {
				$link = $linkRepository->tryGetOneByUrl($linkUrl);
				if ($link === null) {
					$link = new Link();
					$link->url = $linkUrl;
				}
				return $link;
			}, isset($trackData['links']) ? $trackData['links'] : []));
			
			// Add track to repository
			
			$trackRepository->add($track);
			
			// Flush entity manager
			
			$entityManager->flush();
			
			return $this->jsonResponse($track);
		} catch (\Exception $e) {
			return $this->jsonResponseFromException($e);
		}
	}
	
	public function rateAction()
	{
		try {
			// Get dependencies
				
			$entityManager = $this->getEntityManager();
			$trackRepository = $this->getTrackRepository();
				
			// Get parameters
				
			$trackData = $this->getJsonPost();
				
			// Update track
		
			$track = null;
				
			$track = $trackRepository->getOneById($trackData['id']);
				
			$track->rating = $trackData['rating'];

			// Flush entity manager
				
			$entityManager->flush();
				
			return $this->jsonResponse($track);
		} catch (\Exception $e) {
			return $this->jsonResponseFromException($e);
		}
	}
	
	public function deleteAction()
	{
		try {
			// Get dependencies
			
			$entityManager = $this->getEntityManager();
			$trackRepository = $this->getTrackRepository();
			
			// Get parameters
			
			$data = $this->getJsonPost();
			
			// Get track
			
			$track = $trackRepository->getOneById($data['id']);
			
			// Remove track from repository
			
			$trackRepository->remove($track);
			
			// Flush entity manager
			
			$entityManager->flush();
			
			return $this->jsonResponse($track);
		} catch (\Exception $e) {
			return $this->jsonResponseFromException($e);
		}
	}
	
	/**
	 * @return TrackRepository
	 */
	private function getTrackRepository()
	{
		return $this->getContainer()->get(TrackRepository::class);
	}
	
	/**
	 * @return InterpretRepository
	 */
	private function getInterpretRepository()
	{
		return $this->getContainer()->get(InterpretRepository::class);
	}
	
	/**
	 * @return GenreRepository
	 */
	private function getGenreRepository()
	{
		return $this->getContainer()->get(GenreRepository::class);
	}
	
	/**
	 * @return TagTypeRepository
	 */
	private function getTagTypeRepository()
	{
		return $this->getContainer()->get(TagTypeRepository::class);
	}
	
	/**
	 * @return LinkRepository
	 */
	private function getLinkRepository()
	{
		return $this->getContainer()->get(LinkRepository::class);
	}
}

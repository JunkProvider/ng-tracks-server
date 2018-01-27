<?php
namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Track implements \JsonSerializable
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 *
	 * @var integer|null
	 */
	public $id;

	/**
	 * @ORM\Column(type="string")
	 *
	 * @var string
	 */
	public $title;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 *
	 * @var integer
	 */
	public $rating;

	/**
	 * @ORM\ManyToMany(targetEntity="Interpret", cascade={"persist"})
	 *
	 * @var Collection
	 */
	public $interprets;

	/**
	 * @ORM\ManyToMany(targetEntity="Genre", cascade={"persist"})
	 *
	 * @var Collection
	 */
	public $genres;

	/**
	 * @ORM\OneToMany(targetEntity="Tag", mappedBy="taggedTrack", cascade={"persist"}, orphanRemoval=true)
	 *
	 * @var Collection
	 */
	public $tags;

	/**
	 * @ORM\ManyToMany(targetEntity="Link", cascade={"persist"})
	 *
	 * @var Collection
	 */
	public $links;

	public function __construct()
	{
		$this->interprets = new ArrayCollection();
		$this->genres = new ArrayCollection();
		$this->links = new ArrayCollection();
		$this->tags = new ArrayCollection();
	}

	/**
	 *
	 * @param Interpret[] $interprets        	
	 */
	public function setInterprets(array $interprets)
	{
		CollectionUtility::update($this->interprets, $interprets);
	}

	/**
	 *
	 * @param Genre[] $genres        	
	 */
	public function setGenres(array $genres)
	{
		CollectionUtility::update($this->genres, $genres);
	}

	/**
	 *
	 * @param Tag[] $tags        	
	 */
	public function setTags(array $tags)
	{
		CollectionUtility::update($this->tags, $tags);
	}

	/**
	 *
	 * @param Link[] $links        	
	 */
	public function setLinks(array $links)
	{
		CollectionUtility::update($this->links, $links);
	}

	/**
	 *
	 * @see \JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'interprets' => array_values($this->interprets->toArray()),
			'genres' => array_values($this->genres->toArray()),
			'tags' => array_values($this->tags->toArray()),
			'links' => array_values($this->links->toArray()),
			'rating' => $this->rating
		];
	}
}
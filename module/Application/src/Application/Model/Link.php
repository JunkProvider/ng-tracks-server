<?php
namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Link implements \JsonSerializable
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
	public $url;

	/**
	 *
	 * @see \JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'url' => $this->url
		];
	}
}
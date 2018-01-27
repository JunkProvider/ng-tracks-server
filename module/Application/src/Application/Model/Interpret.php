<?php
namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Interpret implements \JsonSerializable
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
	public $name;

	/**
	 *
	 * @see \JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'name' => $this->name
		];
	}
}
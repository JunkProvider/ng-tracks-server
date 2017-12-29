<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TagType implements \JsonSerializable
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * 
	 * @var int|null
	 */
	public $id;
	
	/**
	 * @ORM\Column(type="string")
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		if (!$name || !trim($name)) {
			throw \InvalidArgumentException('Argument "name" can not be null or empty string.');
		}
		$this->name = trim($name);
	}
	
	/**
	 * @return int
	 */
	public function getId()
	{
		if ($this->id === null) {
			throw \LogicException('Can not get id. Entity has no id.');
		}
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * @see \JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
		];
	}
}
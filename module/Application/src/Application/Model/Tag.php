<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Tag implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="Track")
	 *
	 * @var Track
	 */
	private $taggedTrack;
	
	/**
	 * @ORM\ManyToOne(targetEntity="TagType", cascade={"persist"})
	 *
	 * @var TagType
	 */
	private $type;
	
	/**
	 * @ORM\Column(type="integer")
	 *
	 * @var int
	 */
	private $value = 1;
	
	/**
	 * @param Track   $taggedTrack
	 * @param TagType $type
	 */
	public function __construct(Track $taggedTrack, TagType $type)
	{
		$this->taggedTrack = $taggedTrack;
		$this->type = $type;
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
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 * @return int
	 */
	public function getValue()
	{
		return $this->value;
	}
	
	/**
	 * @param int $value
	 */
	public function setValue($value)
	{
		if (!$value) {
			throw \InvalidArgumentException('Argument "value" can not be null.');
		}
		$value = (int)$value;
		if ($value < 1 || !is_finite($value)) {
			throw \InvalidArgumentException('Argument "value" can not be infinite or less than one.');
		}
		$this->value = $value;
	}
	
	/**
	 * @see \JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'type' => $this->type,
			'value' => $this->value,
		];
	}
}
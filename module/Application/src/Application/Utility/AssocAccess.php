<?php

namespace Application\Utility;

interface MixedOrUndefined {
	
}

class Undefined implements MixedOrUndefined
{

}

class Mixed implements MixedOrUndefined
{
	/**
	 * @var mixed
	 */
	private $value;
	
	/**
	 * @param mixed $value
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}
}

class AssocAccess
{
	/**
	 * @var array
	 */
	private $assoc;
	
	/**
	 * @param array $assoc
	 */
	public function __construct(array $assoc)
	{
		$this->assoc = $assoc;
	}
	
	/**
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function getOrDefault($key, $default)
	{
		if (array_key_exists($key, $this->assoc)) {
			return new Mixed($this->assoc[$key]);
		}
		return $default;
	}
	
	/**
	 * @param string $key
	 * 
	 * @throws AssocAccessException
	 * 
	 * @return mixed
	 */
	public function get($key)
	{
		if (array_key_exists($key, $this->assoc)) {
			return $this->assoc[$key];
		}
		throw new AssocAccessException('Can not get value with key "' . $key . '" key does not exist in array.');
	}
	
	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return count($this->assoc) == 0;
	}
	
	/**
	 * @return integer
	 */
	public function count()
	{
		return count($this->assoc);
	}
	
	/**
	 * @return array
	 */
	public function toAssoc()
	{
		return $this->assoc;
	}
}

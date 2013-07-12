<?php

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @author Martin Bažík <martin@bazo.sk>
 * @ODM\Document
 */
class Settings
{

	/**
	 * @var string
	 * @ODM\Id
	 */
	private $id;

	/**
	 * @var string
	 * @ODM\String
	 */
	private $blogName;
	
	/**
	 * @var string
	 * @ODM\String
	 */
	private $blogDescription;
	
	public function getBlogName()
	{
		return $this->blogName;
	}


	public function setBlogName($blogName)
	{
		$this->blogName = $blogName;
		return $this;
	}
	
	public function getBlogDescription()
	{
		return $this->blogDescription;
	}


	public function setBlogDescription($blogDescription)
	{
		$this->blogDescription = $blogDescription;
		return $this;
	}

	public function toArray()
	{
		return [
			'blogName' => $this->blogName,
			'blogDescription' => $this->blogDescription
		];
	}
	
	public function __set($name, $value)
	{
		$reflection = new ReflectionObject($this);
		
		if($reflection->hasProperty($name)) {
			$this->$name = $value;
			return $this;
		}
		
		throw new InvalidArgumentException(sprintf('%s has no property "%s"', get_class($this), $name));
	}

}



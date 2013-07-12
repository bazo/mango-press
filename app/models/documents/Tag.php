<?php

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Nette\Utils\Strings;

/**
 * @author Martin Bažík <martin@bazo.sk>
 * @ODM\Document
 */
class Tag
{

	/**
	 * @var string
	 * @ODM\Id
	 */
	private $id;

	/**
	 * @var string
	 * @ODM\String
	 * @ODM\Index(unique=true, dropDups=true)
	 */
	private $tag;

	/**
	 * @var string
	 * @ODM\String
	 * @ODM\Index(unique=true, dropDups=true)
	 */
	private $webalizedTag;
	
	public function __construct($tag)
	{
		$this->tag = $tag;
		$this->webalizedTag = Strings::webalize($tag);
	}
	
	public function getTag()
	{
		return $this->tag;
	}
	
	public function getWebalizedTag()
	{
		return $this->webalizedTag;
	}

}



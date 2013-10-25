<?php

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nette\Utils\Strings;

/**
 * @author Martin Bažík <martin@bazo.sk>
 * @ODM\Document
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 */
class Post extends Nette\Object
{

	/**
	 * @var string
	 * @ODM\Id
	 */
	private $id;

	/**
	 * @var string
	 * @ODM\String
	 * @ODM\Index(unique=true)
	 */
	private $title;

	/**
	 * @var string
	 * @ODM\String
	 * @ODM\Index(unique=true)
	 */
	private $slug;

	/**
	 * @var string
	 * @ODM\String
	 */
	private $text;

	/**
	 * @var string
	 * @ODM\String
	 */
	private $html;

	/**
	 * @var \DateTime
	 * @ODM\Date
	 * @Gedmo\Timestampable(on="create")
	 */
	private $created;

	/**
	 * @var \DateTime
	 * @ODM\Date
	 * @Gedmo\Timestampable(on="update")
	 */
	private $updated;

	/**
	 * @var \DateTime
	 * @ODM\Date
	 * @Gedmo\Timestampable(on="change", field="isPublished", value="True")
	 */
	private $published;

	/**
	 * @var \DateTime
	 * @ODM\Date
	 */
	private $deleted;

	/**
	 * @var bool
	 * @ODM\Boolean
	 */
	private $isPublished = FALSE;

	/**
	 * @ODM\ReferenceOne(targetDocument="User")
	 * @Gedmo\Blameable(on="create")
	 */
	private $author;

	/**
	 * @ODM\ReferenceOne(targetDocument="User")
	 * @Gedmo\Blameable(on="update")
	 */
	private $updatedBy;

	/**
	 * @ODM\ReferenceOne(targetDocument="User")
	 */
	private $publishedBy;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ODM\Collection
	 */
	private $tags = [];


	public function __construct($title, $text, $html)
	{
		$this->title = $title;
		$this->text = $text;
		$this->html = $html;
		$this->slug = Strings::webalize($title);
	}

	/**
	 * 
	 * @param string $title
	 * @param string $text
	 * @param string $html
	 * @return Post
	 */
	public function update($title, $text, $html)
	{
		$this->title = $title;
		$this->text = $text;
		$this->html = $html;
		$this->slug = Strings::webalize($title);
		return $this;
	}

	/**
	 * Publish post
	 * @return Post
	 */
	public function publish()
	{
		$this->published = new \DateTime;
		$this->isPublished = TRUE;
		return $this;
	}

	/**
	 * Unpublish post
	 * @return Post
	 */
	public function unpublish()
	{
		$this->isPublished = FALSE;
		return $this;
	}

	/**
	 * Set post author
	 * @param User $author
	 * @return Post
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
		return $this;
	}


	public function getId()
	{
		return $this->id;
	}


	public function getTitle()
	{
		return $this->title;
	}


	public function getSlug()
	{
		return $this->slug;
	}


	public function getText()
	{
		return $this->text;
	}


	public function getHtml()
	{
		return $this->html;
	}


	public function getCreated()
	{
		return $this->created;
	}


	public function getUpdated()
	{
		return $this->updated;
	}

	/**
	 * Get the publis time
	 * @return DateTime
	 */
	public function getPublished()
	{
		return $this->published;
	}


	public function isPublished()
	{
		return $this->isPublished;
	}


	/**
	 * Who first wrote this post
	 * @return \User
	 */
	public function getAuthor()
	{
		return $this->author;
	}


	/**
	 * Who last updated this post
	 * @return \User
	 */
	public function getUpdatedBy()
	{
		return $this->updatedBy;
	}


	/**
	 * Who published this post
	 * @return \User
	 */
	public function getPublishedBy()
	{
		return $this->publishedBy;
	}

	/**
	 * Tag post with multiple tags
	 * @param array $tags
	 * @return Post
	 */
	public function tag(array $tags)
	{
		$this->tags = $tags;
		return $this;
	}

	/**
	 * Add multiple tags
	 * @param string $tag
	 * @return Post
	 */
	public function addTags($tags)
	{
		$this->tags = array_merge($this->tags, $tags);
		return $this;
	}
	
	/**
	 * Add a tag
	 * @param string $tag
	 * @return Post
	 */
	public function addTag($tag)
	{
		array_push($this->tags, $tag);
		return $this;
	}

	/**
	 * Remove tag
	 * @param string $tag
	 * @return Post
	 */
	public function removeTag($tag)
	{
		$key = array_search($tag, $this->tags);
		unset($this->tags[$key]);
		return $this;
	}

	/**
	 * @return SplFixedArray
	 */
	public function getTags()
	{
		return $this->tags;
	}


}



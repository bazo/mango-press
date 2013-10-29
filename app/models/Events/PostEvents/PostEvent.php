<?php

namespace MangoPress\Events\PostEvents;

use Symfony\Component\EventDispatcher\Event;

/**
 * Description of PostEvent
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class PostEvent extends Event
{

	/** @var \Post */
	private $post;
	private $tags = [];


	public function __construct(\Post $post, array $tags)
	{
		$this->post = $post;
		$this->tags = $tags;
	}


	/**
	 * @return \Post
	 */
	public function getPost()
	{
		return $this->post;
	}


	public function getTags()
	{
		return $this->tags;
	}


}


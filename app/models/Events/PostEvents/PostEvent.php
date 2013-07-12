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
	
	public function __construct(\Post $post)
	{
		$this->post = $post;
	}
	
	/**
	 * @return \Post
	 */
	public function getPost()
	{
		return $this->post;
	}

}


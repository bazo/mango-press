<?php

namespace MangoPress\WebModule;

use Posts\PostsManager;

/**
 * Posts presenter.
 */
class PostPresenter extends BasePresenter
{

	/** @var PostsManager */
	private $postsManager;

	/** @var \Post */
	private $post;

	public function __construct(PostsManager $postsManager)
	{
		$this->postsManager = $postsManager;
	}


	public function actionDefault($slug)
	{
		$post = $this->postsManager->getPostBySlug($slug);
		
		if(is_null($post) or !$post->isPublished()) {
			throw new \Nette\Application\BadRequestException(sprintf('Post with slug "%s" not found.', $slug));
		}
		
		$this->post = $post;
	}


	public function renderDefault()
	{
		$this->template->post = $this->post;
	}

}



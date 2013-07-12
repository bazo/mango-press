<?php

namespace MangoPress\WebModule;

use Posts\PostsManager;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	/** @var PostsManager */
	private $postsManager;

	/** @var \Post[] */
	private $posts;

	/** @var \Posts\PostCriteria */
	private $criteria;

	public function __construct(PostsManager $postsManager)
	{
		$this->postsManager = $postsManager;
		$this->criteria = new \Posts\PostCriteria;
	}


	public function actionDefault()
	{
		
	}

	public function actionSearch($search)
	{
		$this->criteria->searchTerm = $search;
		$this->template->activeTag = $search;
		
		$this->view = 'default';
	}
	
	public function actionTag($search)
	{
		$this->criteria->tag = $search;
		$this->template->activeTag = $search;
		
		$this->view = 'default';
	}

	public function actionFeed($tag = NULL) 
	{
		$this->criteria->tag = $tag;
	}

	public function renderDefault()
	{
		$this->posts = $this->postsManager->searchPosts($this->criteria);
		$this->template->posts = $this->posts;
	}
	
	public function renderFeed()
	{
		$this->posts = $this->postsManager->getPostsByCriteria($this->criteria);
		$this->template->posts = $this->posts;
	}


}




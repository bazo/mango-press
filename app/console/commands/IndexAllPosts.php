<?php

namespace Console\Command;

use Symfony\Component\Console;
use Posts\PostsManager;
use Posts\PostsIndexer;

/**
 * Create user command
 * @author Martin Bažík <martin@bazo.sk>
 */
class IndexAllPosts extends Console\Command\Command
{

	/** @var PostsManager */
	private $postsManager;

	/** @var PostsIndexer */
	private $postsIndexer;


	function __construct(PostsManager $postsManager, PostsIndexer $postsIndexer)
	{
		$this->postsManager = $postsManager;
		$this->postsIndexer = $postsIndexer;
		parent::__construct(NULL);
	}


	protected function configure()
	{
		$this
			->setName('posts:index')
			->setDescription('Creates a new user account')
		;
	}


	protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
	{
		$posts = $this->postsManager->getAllPublishedPosts();
		foreach($posts as $post) {
			$this->postsIndexer->indexPost($post);
		}
	}


}



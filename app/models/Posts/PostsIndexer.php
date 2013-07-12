<?php

namespace Posts;

use Elastica\Client;
use Posts\PostsManager;
use Nette\Utils\Strings;

/**
 * Description of PostsIndexer
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class PostsIndexer
{
	/** @var Client */
	private $elastica;

	/** @var PostsManager */
	private $postsManager;
	
	public function __construct(Client $elastica, PostsManager $postsManager)
	{
		$this->elastica = $elastica;
		$this->postsManager = $postsManager;
	}
	
	public function process(\Queue\Message $message)
	{
		$payload = json_decode($message->getPayload());
		$id = $payload->id;
		$post = $this->postsManager->getPostById($id);
		
		$this->indexPost($post);
	}
	
	public function indexPost(\Post $post)
	{
		$html = strip_tags($post->getHtml());
		
		$webalizedTags = array_map(function($tag){
			return Strings::webalize($tag);
		}, $post->getTags());
		
		$webalizedTags = array_unique($webalizedTags);
		
		$data = [
			'title' => $post->getTitle(),
			'slug' => $post->getSlug(),
			'html' => $post->getHtml(),
			'published' => $post->getPublished()->format('d.m.Y H:i:s'),
			'tags' => $webalizedTags,
			'author' => $post->getAuthor()->getLogin()
		];
		
		$indexName = 'web';
		$typeName = 'post';
		
		$index = $this->elastica->getIndex($indexName);

		$type = $index->getType($typeName);

		$document = new \Elastica\Document($post->getId(), $data, $type, $indexName);
		$type->addDocument($document);
		$index->refresh();
	}


}


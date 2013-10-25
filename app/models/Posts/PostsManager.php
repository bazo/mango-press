<?php

namespace Posts;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Elastica\Client;

/**
 * UserManager
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class PostsManager extends \BaseManager
{

	/** @var Client */
	private $elastica;
	
	public function __construct(DocumentManager $dm, EventDispatcher $mediator, Client $elastica)
	{
		parent::__construct($dm, $mediator);
		$this->elastica = $elastica;
	}
	
	public function getPostById($id)
	{
		return $this->dm->getRepository('\Post')->find($id);
	}


	public function getPostBySlug($slug)
	{
		return $this->dm->getRepository('\Post')->findOneBy(['slug' => $slug]);
	}


	/**
	 * Create new post
	 * @param \User $author
	 * @param string $title
	 * @param string $text
	 * @param string $html
	 * @param array tags
	 * @param bool publish
	 */
	public function createPost($title, $text, $html, $tags, $publish)
	{
		$post = new \Post($title, $text, $html);
		$post->tag($tags);

		$publish === TRUE ? $post->publish() : $post->unpublish();

		$this->dm->persist($post);
		$this->dm->flush();

		$event = new \MangoPress\Events\PostEvents\PostEvent($post);
		$this->mediator->dispatch(\MangoPress\Events\PostEvents::POST_CREATED, $event);
	}


	/**
	 * @param \User $updatedBy
	 * @param \Post $post
	 * @param string $title
	 * @param string $text
	 * @param string $html
	 * @param array tags
	 * @param bool publish
	 */
	public function updatePost(\Post $post, $title, $text, $html, $tags, $publish)
	{
		$post->update($title, $text, $html);
		$post->tag($tags);

		$publish === TRUE ? $post->publish() : $post->unpublish();

		$this->dm->persist($post);
		$this->dm->flush();

		$event = new \MangoPress\Events\PostEvents\PostEvent($post);
		$this->mediator->dispatch(\MangoPress\Events\PostEvents::POST_UPDATED, $event);
	}


	public function getAllPosts()
	{
		return $this->dm->getRepository('\Post')->findAll();
	}


	public function getAllPublishedPosts()
	{
		$qb = $this->dm->getRepository('Post')->createQueryBuilder();

		$qb->field('isPublished')->equals(TRUE)->sort('published', 'desc');

		return $qb->getQuery()->execute();
	}


	public function getPostsByCriteria(PostCriteria $criteria)
	{
		$qb = $this->dm->getRepository('Post')->createQueryBuilder();

		$qb->field('isPublished')->equals($criteria->published);

		if (!is_null($criteria->tag)) {
			$qb->field('tags')->in([$criteria->tag]);
		}

		return $qb->getQuery()->execute();
	}


	public function publishPost($id)
	{
		$qb = $this->dm->getRepository('\Post')->createQueryBuilder();

		$qb->update()
				->field('isPublished')->set(TRUE)
				->field('_id')->equals($id)
				->getQuery()->execute();
	}


	public function unpublishPost($id)
	{
		$qb = $this->dm->getRepository('\Post')->createQueryBuilder();

		$qb->update()
				->field('isPublished')->set(FALSE)
				->field('_id')->equals($id)
				->getQuery()->execute();
	}


	public function searchAllPosts()
	{
		return $this->elastica->getIndex('web')->getType('post')->search();
	}
	
	public function searchPosts(PostCriteria $criteria)
	{
		if($criteria->searchTerm) {
			try {
				return $this->searchPostsBySearchTerm($criteria->searchTerm);
			} catch (\Elastica\Exception\Connection\HttpException $e) {
				//elasticsearch down?
				return $this->getPostsByCriteria($criteria)->getResults();
			}
		}
		
		if($criteria->tag) {
			try {
				return $this->searchPostsByTag($criteria->tag)->getResults();
			} catch (\Elastica\Exception\Connection\HttpException $e) {
				//elasticsearch down?
				return $this->getPostsByCriteria($criteria);
			}
		}
		
		try {
			return $this->searchAllPosts()->getResults();
		} catch (\Elastica\Exception\Connection\HttpException $e) {
			//elasticsearch down?
			return $this->getAllPublishedPosts();
		}
	}
	
	public function searchPostsBySearchTerm($searchTerm)
	{
		$queryDefinition = [
			'query' => [
				'bool' => [
					'must' => [
						[
							'query_string' => [
								'default_field' => '_all',
								'query' => $searchTerm
							]
						]
					],
					'must_not' => [],
					'should' => []
				],
			],
			'highlight' => [
				'fields'=> [
					'html' => [],
					'title' => []
				]
			],
			'from' => 0,
			'size' => 50,
			'sort' => [
				['_type' => 'desc'],
			],
			'facets' => [
				'types' => [
					'terms' => ['field' => '_type']
				]
			]
		];

		$query = new \Elastica\Query($queryDefinition);
		return $this->elastica->getIndex('web')->getType('post')->search($query);
	}
	
	public function searchPostsByTag($tag)
	{
		$queryDefinition = [
			'query' => [
				'bool' => [
					'must' => [
						[
							'term' => [
								'post.tag' => $tag,
							]
						]
					],
					'must_not' => [],
					'should' => []
				],
			],
			'highlight' => [
				'fields'=> [
					'html' => [],
					'title' => []
				]
			],
			'from' => 0,
			'size' => 50,
			'sort' => [
				['_type' => 'desc'],
			],
			'facets' => [
				'types' => [
					'terms' => ['field' => '_type']
				]
			]
		];

		$query = new \Elastica\Query($queryDefinition);
		return $this->elastica->getIndex('web')->getType('post')->search($query);
	}


}



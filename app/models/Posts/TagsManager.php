<?php

namespace Posts;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * UserManager
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class TagsManager extends \BaseManager implements EventSubscriberInterface
{

	public static function getSubscribedEvents()
	{
		return [
			\MangoPress\Events\PostEvents::POST_CREATED => [
				['updateTags', 0],
			],
			\MangoPress\Events\PostEvents::POST_UPDATED => [
				['updateTags', 0],
			],
		];
	}


	public function updateTags(\MangoPress\Events\PostEvents\PostEvent $event)
	{
		$post = $event->getPost();

		$tags = $post->getTags();
		$allTagsQuery = $this->dm->createQueryBuilder('Tag')
				->select('tag')
				->getQuery();
		
		$allTagsQuery->setHydrate(FALSE);

		$allTagsCursor = $allTagsQuery->execute();
		$allTags = [];
		foreach ($allTagsCursor as $tag) {
			$allTags[] = $tag['tag'];
		}
		
		foreach ($tags as $tagName) {
			if ($tagName === '' or $tagName === NULL) {
				continue;
			}
			
			if(in_array($tagName, $allTags)) {
				continue;
			}
			
			$tag = new \Tag($tagName);

			$this->dm->persist($tag);
		}
		$this->dm->flush();
	}
	
	public function getAllTags(TagsCriteria $criteria)
	{
		$allTagsQuery = $this->dm->createQueryBuilder('Tag')
				->getQuery();
		
		$allTagsQuery->setHydrate($criteria->hydrate);
		$allTagsCursor = $allTagsQuery->execute();
		
		$allTags = [];
		foreach($allTagsCursor as $tag) {
			$allTags[$tag['tag']] = $tag['webalizedTag'];
		}
		
		return $allTags;
	}


}



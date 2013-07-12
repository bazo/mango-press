<?php

namespace Search;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * IndexingListener
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class IndexingListener implements EventSubscriberInterface
{

	/** @var \Queue\QueueManager */
	private $qm;


	function __construct(\Queue\QueueManager $qm)
	{
		$this->qm = $qm;
	}


	public static function getSubscribedEvents()
	{
		return [
			\MangoPress\Events\PostEvents::POST_CREATED => [
				['indexPostAsync', 0],
			],
			\MangoPress\Events\PostEvents::POST_UPDATED => [
				['indexPostAsync', 0],
			],
		];
	}


	public function indexPostAsync(\MangoPress\Events\PostEvents\PostEvent $event)
	{
		$post = $event->getPost();

		$data = [
			'id' => $post->getId()
		];
		$message = new \Queue\Message(json_encode($data));

		$this->qm->publishMessage('indexing', $message);
	}


}


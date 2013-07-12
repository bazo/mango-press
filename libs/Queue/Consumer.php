<?php

namespace Queue;

/**
 * Message Queue Consumer
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class Consumer
{

	/** @var QueueManager */
	private $qm;


	public function __construct(QueueManager $qm)
	{
		$this->qm = $qm;
	}


	/**
	 * Bind a queue to listen on
	 * @param type $queue
	 * @return IronMQConsumer
	 */
	public function bindQueue($queue)
	{
		$this->queue = $queue;
		return $this;
	}


	/**
	 * Add a callback
	 * @param Callable $callback
	 * @return IronMQConsumer
	 */
	public function addCallback(Callable $callback)
	{
		$this->callbacks[spl_object_hash($callback)] = $callback;
		return $this;
	}


	public function consume($queue)
	{
		while (TRUE) {
			$message = $this->qm->getMessage($queue);
			if ($message !== NULL) {
				$this->fireCallbacks($message);
			}
		}
	}


	private function fireCallbacks(Message $message)
	{
		foreach ($this->callbacks as $callback) {
			if (!$message->isPropagationStopped()) {
				$callback($message);
			} else {
				break;
			}
		}
	}


}


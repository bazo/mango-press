<?php

namespace Queue;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Message Queue Consumer
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class Consumer
{

	/** @var QueueManager */
	private $qm;

	/** @var OutputInterface */
	private $output;

	/** @var int */
	private $sleep = 10;


	public function __construct(QueueManager $qm, OutputInterface $output)
	{
		$this->qm = $qm;
		$this->output = $output;
		$this->output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
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
	 * @return Consumer
	 * @param callable $callback
	 * @return Consumer
	 */
	public function addCallback(callable $callback)
	{
		$this->callbacks[spl_object_hash($callback)] = $callback;
		return $this;
	}


	/**
	 * Set how many seconds to sleep between queue poll
	 * @param int $sleep
	 * @return Consumer
	 */
	public function setSleep($sleep)
	{
		$this->sleep = $sleep;
		return $this;
	}


	public function consume($queue)
	{
		while (TRUE) {
			$message = $this->qm->getMessage($queue);
			$time = date('d.m.Y H:i:s');
			
			if ($message !== NULL) {
				$this->output->writeln(sprintf('[%s] Processing message.', $time));
				$this->fireCallbacks($message);
			} else {
				$this->output->writeln(sprintf('[%s] No message.', $time));
			}
			
			$next = date('d.m.Y H:i:s', time() + $this->sleep);
			$this->output->writeln(sprintf('Next try at %s in %d seconds', $next, $this->sleep));
			sleep($this->sleep);
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


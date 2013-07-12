<?php

namespace Queue;

/**
 * Description of Message
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class Message
{

	/** @var int */
	private $timestamp;

	/** @var string */
	private $payload;

	/**
	 * @var Boolean Whether no further event listeners should be triggered
	 */
	private $propagationStopped = FALSE;

	/**
	 * @param string $payload
	 */
	function __construct($payload)
	{
		$this->payload = $payload;
	}


	/**
	 * @return string
	 */
	public function getPayload()
	{
		return $this->payload;
	}

	
	/**
	 * Stops the propagation of the message to further callbacks.
	 *
	 * If multiple callbacks are connected to the same queue, no
	 * further callbacks will be triggered once any consumer calls
	 * stopPropagation().
	 *
	 */
	public function stopPropagation()
	{
		$this->propagationStopped = TRUE;
	}
	
	public function isPropagationStopped()
	{
		return $this->propagationStopped;
	}



}


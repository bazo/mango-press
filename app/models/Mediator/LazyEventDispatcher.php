<?php

namespace Mediator;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of LazyEventDispatcher
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class LazyEventDispatcher extends EventDispatcher
{

	/** @var \SystemContainer */
	private $container;

	/** @var array */
	protected $listeners = array();


	public function __construct(\SystemContainer $container, array $listenersByEvent)
	{
		$this->container = $container;
		
		foreach($listenersByEvent as $eventName => $eventListeners) {
			foreach($eventListeners as $priority => $listeners) {
				foreach($listeners as $listener) {
					$this->addListener($eventName, $listener, $priority);
				}
			}
		}
	}


	/**
	 * Loads service from DI container
	 * Triggers the listeners of an event.
	 *
	 * This method can be overridden to add functionality that is executed
	 * for each listener.
	 *
	 * @param array[callback] $listeners The event listeners.
	 * @param string          $eventName The name of the event to dispatch.
	 * @param Event           $event     The event object to pass to the event handlers/listeners.
	 */
	protected function doDispatch($listeners, $eventName, Event $event)
	{
		foreach ($listeners as $listenerParams) {
			$subscriber = $listenerParams[0];
			$method = $listenerParams[1];
			$service = $subscriber;
			if (is_string($subscriber)) {
				$service = $this->container->getService($subscriber);
			}
			$listener = [$service, $method];
			call_user_func($listener, $event);
			if ($event->isPropagationStopped()) {
				break;
			}
		}
	}


}


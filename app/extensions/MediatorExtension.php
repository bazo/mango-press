<?php

namespace Extensions;

use Nette\Framework;

/**
 * Lazy mediator
 *
 * @author Martin Bažík ?<martin@bazo.sk>
 */
class MediatorExtension extends \Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		
		$containerBuilder
				->addDefinition($this->prefix('eventDispatcher'))
				->setClass('Mediator\LazyEventDispatcher');

		$containerBuilder
				->addDefinition('mediator')
				->setClass('Mediator\LazyEventDispatcher')
				->setFactory('@container::getService', array($this->prefix('eventDispatcher')))
				->setAutowired(FALSE);
	}
	
	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();
		
		$subscribers = $containerBuilder->findByTag('subscriber');
		
		$listenersMap = [];
		foreach ($subscribers as $subscriberName => $subscriber) {
			$definition = $containerBuilder->getDefinition($subscriberName);
			$subscribedEvents = call_user_func_array($definition->class . '::getSubscribedEvents', []);
			
			foreach($subscribedEvents as $eventName => $callbacks) {
				foreach($callbacks as $parameters) {
					$callback = $parameters[0];
					$priority = $parameters[1];
					
					$listenersMap[$eventName][$priority][] = [$subscriberName, $callback];
					
				}
			}
		}
		
		$containerBuilder->getDefinition($this->prefix('eventDispatcher'))
				->setArguments(['@container', $listenersMap]);
	}

}


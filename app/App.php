<?php

final class App
{

	const 
		NAME = 'MangoPress',
		VERSION = '0.1.0'
	;

	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new \Nette\StaticClassException;
	}
}

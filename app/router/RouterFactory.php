<?php

namespace App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		
		$adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('admin/<presenter>/<action>[/<id>]', 'homepage:default');
		
		$webRouter = new RouteList('Web');
		$webRouter[] = new Route('/rss.xml[/<tag>]', 'homepage:feed');
		$webRouter[] = new Route('<slug>', 'post:default');
		$webRouter[] = new Route('<action search|tag>/<search>', 'homepage:search');
		$webRouter[] = new Route('<presenter>/<action>', 'homepage:default');
		
		$router[] = $adminRouter;
		$router[] = $webRouter;
		
		return $router;
	}

}

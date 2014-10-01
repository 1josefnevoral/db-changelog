<?php

namespace Lovec\DbChangelog\Router;

use Nette\Application\Routers\Route;


class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		return new Route('<module db-changelog>/<presenter>/<action>', 'Changelog:default');
	}

}

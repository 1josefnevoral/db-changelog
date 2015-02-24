<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Router;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;


class RouterFactory
{

	/**
	 * @return IRouter
	 */
	public function createRouter()
	{
		return new Route('<module db-changelog>/<presenter>/<action>', 'Changelog:default');
	}

}

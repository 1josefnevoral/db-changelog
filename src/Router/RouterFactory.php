<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file LICENSE that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Router;

use Flame\Modules\Application\IRouterFactory;
use Nette\Application\Routers\Route;

class RouterFactory implements IRouterFactory
{

	/**
	 * @return IRouter
	 */
	public function createRouter()
	{
		return new Route('<module db-changelog>/<presenter>/<action>', 'Changelog:default');
	}

}

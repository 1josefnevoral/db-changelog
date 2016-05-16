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
	/** @var bool */
	private $https = FALSE;


	/**
	 * @param bool
	 */
	public function __construct(
		$https = FALSE
	) {
		$this->https = (bool) $https;
	}


	/**
	 * @return IRouter
	 */
	public function createRouter()
	{
		$flags = ($this->https ? Route::SECURED : 0);

		return new Route('<module db-changelog>/<presenter>/<action>', 'Changelog:default', $flags);
	}

}

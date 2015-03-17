<?php

namespace Lovec\DbChangelog\Tests;

use Nette\Configurator;
use Nette\DI\Container;


class ContainerFactory
{

	/**
	 * @return Container
	 */
	public function create()
	{
		$config = new Configurator;
		$config->setTempDirectory(TEMP_DIR);
		$config->addConfig(__DIR__ . '/config.neon');
		return $config->createContainer();
	}

}

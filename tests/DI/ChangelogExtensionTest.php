<?php

namespace Lovec\DbChangelog\Tests\DI;

use Lovec\DbChangelog\Tests\ContainerFactory;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;


class ChangelogExtensionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$containerFactory = new ContainerFactory;
		$this->container = $containerFactory->create();
	}


	public function testInstances()
	{
		$this->assertInstanceOf(
			'Lovec\DbChangelog\ChangelogManager',
			$this->container->getByType('Lovec\DbChangelog\ChangelogManager')
		);

		$this->assertInstanceOf(
			'Lovec\DbChangelog\Events\OnRequest',
			$this->container->getByType('Lovec\DbChangelog\Events\OnRequest')
		);

		$this->assertInstanceOf(
			'Lovec\DbChangelog\Model\Changelog',
			$this->container->getByType('Lovec\DbChangelog\Model\Changelog')
		);

		$this->assertInstanceOf(
			'Lovec\DbChangelog\Components\AddToChangelog\ControlFactory',
			$this->container->getByType('Lovec\DbChangelog\Components\AddToChangelog\ControlFactory')
		);

		$this->assertInstanceOf(
			'Lovec\DbChangelog\Router\RouterFactory',
			$this->container->getByType('Lovec\DbChangelog\Router\RouterFactory')
		);
	}

}

<?php

/**
 * @testCase
 */

namespace LovecTests\Cleaner;

use Nette;
use Tester\Assert;
use Tester\TestCase;


$container = require_once __DIR__ . '/../../bootstrap.php';


class ChangelogExtensionTest extends TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function testInstances()
	{
		Assert::type(
			'Lovec\DbChangelog\ChangelogManager',
			$this->container->getByType('Lovec\DbChangelog\ChangelogManager')
		);

		Assert::type(
			'Lovec\DbChangelog\Events\OnRequest',
			$this->container->getByType('Lovec\DbChangelog\Events\OnRequest')
		);

		Assert::type(
			'Lovec\DbChangelog\Model\Changelog',
			$this->container->getByType('Lovec\DbChangelog\Model\Changelog')
		);

		Assert::type(
			'Lovec\DbChangelog\Components\AddToChangelog\ControlFactory',
			$this->container->getByType('Lovec\DbChangelog\Components\AddToChangelog\ControlFactory')
		);

		Assert::type(
			'Lovec\DbChangelog\Router\RouterFactory',
			$this->container->getByType('Lovec\DbChangelog\Router\RouterFactory')
		);
	}

}


\run(new ChangelogExtensionTest($container));

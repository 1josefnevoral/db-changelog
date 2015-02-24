<?php

namespace Lovec\DbChangelog\Tests\DI;

use Lovec\DbChangelog\DI\ChangelogExtension;
use Lovec\DbChangelog\Tests\ContainerFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;


class ChangelogExtensionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var ChangelogExtension
	 */
	private $extension;


	protected function setUp()
	{
		$compiler = new Compiler(new ContainerBuilder);
		$compiler->compile(['parameters' => [
			'appDir' => TEMP_DIR
		]], NULL, NULL);
		$this->extension = new ChangelogExtension;
		$this->extension->setCompiler($compiler, 'compiler');
	}


	public function testLoadConfiguration()
	{
		$this->extension->loadConfiguration();
		$builder = $this->extension->getContainerBuilder();
		$builder->prepareClassList();

		$changelogManagerName = $builder->getByType('Lovec\DbChangelog\ChangelogManager');
		$changelogManagerDefinition = $builder->getDefinition($changelogManagerName);

		$this->assertSame('Lovec\DbChangelog\ChangelogManager', $changelogManagerDefinition->getClass());
	}

}

<?php

namespace Lovec\DbChangelog\Tests\Model;

use Lovec\DbChangelog\Model\Changelog;
use Lovec\DbChangelog\Tests\ContainerFactory;
use Lovec\DbChangelog\Tests\DatabaseLoader;
use Nette\Utils\DateTime;
use PHPUnit_Framework_TestCase;


class ChangelogTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Changelog
	 */
	private $changelogModel;


	public function __construct()
	{
		$containerFactory = new ContainerFactory;
		$this->container = $containerFactory->create();
	}


	protected function setUp()
	{
		$this->changelogModel = $this->container->getByType('Lovec\DbChangelog\Model\Changelog');

		/** @var DatabaseLoader $databaseLoader */
		$databaseLoader = $this->container->getByType('Lovec\DbChangelog\Tests\DatabaseLoader');
		$databaseLoader->loadTables();

		$this->changelogModel->insert([
			'file' => 'someFile',
			'query' => 'someQuery',
			'ins_dt' => (string) new DateTime,
			'upd_dt' => (string) new DateTime
		]);
	}


	public function testCounts()
	{
		$this->assertCount(1, $this->changelogModel->getNewQueries());
		$this->assertSame(1, $this->changelogModel->getNotExecutedCount());
		$this->assertCount(1, $this->changelogModel->getQueriesToExecute());
	}


	public function testIsFileInserted()
	{
		$fileInfo = new \SplFileInfo('SomeFile.sql');
		$this->assertFalse($this->changelogModel->isFileInserted($fileInfo));
	}


//	public function testExecuteQueries()
//	{
//		$queriesToExecute = $this->changelogModel->getQueriesToExecute();
//		$this->changelogModel->executeQueries($queriesToExecute);
//	}

}

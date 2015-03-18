<?php

namespace Lovec\DbChangelog\Tests\Model;

use Lovec\DbChangelog\Model\Changelog;
use Lovec\DbChangelog\Tests\ContainerAwareTestCase;
use Lovec\DbChangelog\Tests\DatabaseLoader;
use Nette\Utils\DateTime;
use SplFileInfo;


class ChangelogTest extends ContainerAwareTestCase
{

	/**
	 * @var Changelog
	 */
	private $changelogModel;


	protected function setUp()
	{
		$this->changelogModel = $this->container->getByType(Changelog::class);

		/** @var DatabaseLoader $databaseLoader */
		$databaseLoader = $this->container->getByType(DatabaseLoader::class);
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
		$fileInfo = new SplFileInfo('SomeFile.sql');
		$this->assertFalse($this->changelogModel->isFileInserted($fileInfo));
	}

}

<?php

namespace Lovec\DbChangelog\Tests;

use Nette\Database\Context;


class DatabaseLoader
{

	/**
	 * @var Context
	 */
	private $database;


	public function __construct(Context $database)
	{
		$this->database = $database;
	}


	public function loadTables()
	{
		$createTableQuery = file_get_contents(__DIR__ . '/../db/changelog-table-postgres.sql');
		$this->database->query($createTableQuery);
	}

}

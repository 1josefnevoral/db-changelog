<?php

namespace Lovec\DbChangelog\Tests;

use Nette\Database\Connection;
use Nette\Database\Helpers;


class DatabaseLoader
{

	/**
	 * @var Connection
	 */
	private $connection;


	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}


	public function loadTables()
	{
		Helpers::loadFromFile($this->connection, __DIR__ . '/../db/changelog-table-sqlite.sql');
	}

}

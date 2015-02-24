<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Model;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use SplFileInfo;


class Changelog
{

	/**
	 * @var Selection
	 */
	private $table;

	/**
	 * @var Context
	 */
	private $database;


	/**
	 * @param string $tableName
	 * @param Context $database
	 */
	public function __construct($tableName, Context $database)
	{
		$this->table = $database->table($tableName);
		$this->database = $database;
	}


	public function insert(array $data)
	{
		$this->table->insert($data);
	}


	/**
	 * @return Selection
	 */
	public function getNewQueries()
	{
		return $this->table->where('executed', 0)
			->order('ins_dt');
	}


	/**
	 * @param Selection|ActiveRow[] $queries
	 * @return array
	 */
	public function executeQueries(Selection $queries)
	{
		$errors = [];
		foreach ($queries as $query) {
			try {
				$this->database->query($query->query);

				// update query as executed
				$query->update(['executed' => 1]);

			} catch (\Exception $e) {
				// save information about error in query
				$query->update(['error' => $e->getMessage()]);
				$errors[$query->getPrimary()] = $e->getMessage();
			}
		}

		return $errors;
	}


	/**
	 * @return int
	 */
	public function getNotExecutedCount()
	{
		return $this->getQueriesToExecute()
			->count('*');
	}


	/**
	 * @return int
	 */
	public function isFileInserted(SplFileInfo $file)
	{
		return (bool) $this->table->where('file', $file->getBasename())
			->count();
	}


	/**
	 * @return Selection
	 */
	public function getQueriesToExecute()
	{
		return $this->table->where(['executed' => 0]);
	}

}

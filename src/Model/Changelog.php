<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file LICENSE that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Model;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use SplFileInfo;

class Changelog
{
	/**
	 * @var string
	 */
	private $tableName;

	/**
	 * @var Context
	 */
	private $database;


	/**
	 * @param string
	 * @param Context
	 */
	public function __construct($tableName, Context $database)
	{
		$this->tableName = $tableName;
		$this->database = $database;
	}


	/**
	 * @param array
	 */
	public function insert(array $data)
	{
		$this->getTable()->insert($data);
	}


	/**
	 * @return Selection
	 */
	public function getNewQueries()
	{
		return $this->getTable()->where(['executed' => 0])
			->order('ins_dt');
	}


	/**
	 * @param Selection|ActiveRow[]
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
	 * @param SplFileInfo
	 * @return bool
	 */
	public function isFileInserted(SplFileInfo $file)
	{
		return (bool) $this->getTable()->where('file', $file->getBasename('.sql'))
			->count('id');
	}


	/**
	 * @return Selection
	 */
	public function getQueriesToExecute()
	{
		return $this->getTable()->where(['executed' => 0]);
	}


	/**
	 * @return Selection
	 */
	private function getTable()
	{
		return $this->database->table($this->tableName);
	}

}

<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Model;

use Nette;
use Nette\Database\Context;
use Nette\Database\Table\Selection;


class Changelog extends Nette\Object
{

	/**
	 * @var string
	 */
	private $tableName;

	/**
	 * @var Context
	 */
	private $db;


	/**
	 * @param string $tableName
	 * @param Context $db
	 */
	public function __construct($tableName, Context $db)
	{
		$this->db = $db;
		$this->tableName = $tableName;
	}


	/**
	 * @return Selection
	 */
	public function getTable()
	{
		return $this->db->table($this->tableName);
	}


	/**
	 * @return Selection
	 */
	public function getNewQueries()
	{
		return $this->getTable()
			->where('executed', 0)
			->order('ins_dt');
	}


	/**
	 * @param Selection|Nette\Database\Table\ActiveRow[] $queries
	 * @return array
	 */
	public function executeQueries(Selection $queries)
	{
		$errors = array();
		foreach ($queries as $query) {
			try {
				$this->db->query($query->query);

				// update query as executed
				$query->update(array('executed' => 1));

			} catch (\Exception $e) {
				// save information about error in query
				$query->update(array('error' => $e->getMessage()));
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
	public function isFileInserted(\SplFileInfo $file)
	{
		return (bool) $this->getTable()
			->where('file', $file->getBasename())
			->count();
	}


	/**
	 * @return Selection
	 */
	public function getQueriesToExecute()
	{
		return $this->getTable()
			->where(array('executed' => 0));
	}

}

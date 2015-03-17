<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file LICENSE that was distributed with this source code.
 */

namespace Lovec\DbChangelog;

use Lovec\DbChangelog\Model\Changelog;
use Nette\UnexpectedValueException;
use Nette\Utils\Finder;
use Nette\Utils\Strings;
use SplFileInfo;


/**
 * Handles changes in database structure.
 */
class ChangelogManager
{

	/**
	 * @var string
	 */
	private $changelogPath;

	/**
	 * @var Changelog
	 */
	private $changelogTable;


	/**
	 * @param string $changelogPath
	 * @param Changelog $changelogTable
	 */
	public function __construct($changelogPath, Changelog $changelogTable)
	{
		$this->changelogPath = $changelogPath;
		$this->changelogTable = $changelogTable;
	}


	/**
	 * @param string $description
	 * @param array $queries
	 * @return bool
	 */
	public function addNewQueries($description, array $queries)
	{
		// create new file and save queries there
		$time = time();
		$filename = $time . '_' . Strings::webalize(Strings::truncate($description, 30)) . '.sql';
		file_put_contents($this->changelogPath . $filename, $queries);

		// save queries into database table changelog
		$queries = explode(';', $queries);
		foreach ($queries as $query) {
			$query = trim($query);
			if (empty($query)) {
				continue;
			}
			$data = [
				'file' => $filename,
				'description' => $description,
				'query' => $query,
				'executed' => 1,
				'ins_timestamp' => $time,
				'ins_dt' => new \DateTime
			];
			$this->changelogTable->insert($data);
		}
		return TRUE;
	}


	/**
	 * Checks if in database table changelog are some changes that
	 * were not executed yet.
	 */
	public function importNewChangelogData()
	{
		if ($this->changelogTable->getNotExecutedCount()) {
			return TRUE;
		}

		$newChanges = FALSE;

		// load files with database changes
		foreach ($this->findSqlFiles() as $key => $file) {
			// check if file was already inserted into changelog table
			$filename = $file->getBasename('.sql');
			$fileParts = explode('_', $filename);
			if (count($fileParts) < 2) {
				throw new UnexpectedValueException(
					'Changelog file "' . $filename . '" has unexpected form. It should be %timestamp%_%name%.sql'
				);
			}

			if ($this->changelogTable->isFileInserted($file)) {
				// this file content was already inserted
				continue;
			}
			$newChanges = TRUE;

			// content of the file is not in database table, insert it
			$filePathname = $file->getPathname();
			$fileContent = file_get_contents($filePathname);
			$queries = explode(';', $fileContent);
			foreach ($queries as $query) {
				$query = trim($query);
				if (empty($query)) {
					continue;
				}
				$data = [
					'file' => $file->getBasename(),
					'description' => substr($fileParts[1], 0),
					'query' => $query,
					'executed' => 0,
					'ins_timestamp' => $fileParts[0],
					'ins_dt' => new \DateTime
				];
				$this->changelogTable->getTable()->insert($data);
			}
		}
		return $newChanges;
	}


	/**
	 * @return array
	 */
	public function executeNewQueries()
	{
		$queriesToExecute = $this->changelogTable->getNewQueries();
		return $this->changelogTable->executeQueries($queriesToExecute);
	}


	/**
	 * Check if files have changed.
	 *
	 * @return bool
	 */
	public function haveFilesChanged()
	{
		$filesHash = $this->generateFilesHash();
		$oldFileHash = $this->getFilesHash();
		$this->saveFilesHash($filesHash);

		if ($oldFileHash === NULL) {
			return TRUE;

		} elseif ($filesHash !== $oldFileHash) {
			return TRUE;

		} else {
			return FALSE;
		}
	}


	/**
	 * @param string $hash
	 */
	private function saveFilesHash($hash)
	{
		file_put_contents($this->getFilesHashPath(), $hash);
	}


	/**
	 * @return string|NULL
	 */
	private function getFilesHash()
	{
		if (file_exists($this->getFilesHashPath())) {
			return file_get_contents($this->getFilesHashPath());
		}

		return NULL;
	}


	/**
	 * @return string
	 */
	private function getFilesHashPath()
	{
		return $this->changelogPath . DIRECTORY_SEPARATOR . 'filesHash.txt';
	}


	/**
	 * @return string
	 */
	private function generateFilesHash()
	{
		$fileNames = [];
		foreach ($this->findSqlFiles() as $key => $file) {
			$fileNames[] = $file->getFilename();
		}

		return sha1(implode($fileNames));
	}


	/**
	 * @return SplFileInfo[]
	 */
	private function findSqlFiles()
	{
		return Finder::findFiles('*.sql')->in($this->changelogPath);
	}

}

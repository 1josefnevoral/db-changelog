<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Lovec\DbChangelog\App\Presenters;

use Lovec\DbChangelog\ChangelogManager;
use Lovec\DbChangelog\Components\AddToChangelog;
use Lovec\DbChangelog\Components\AddToChangelog\AddToChangelogControlFactory;
use Lovec\DbChangelog\Model\Changelog;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;


/**
 * @property \stdClass|Template $template
 */
class ChangelogPresenter extends Presenter
{

	/**
	 * @var ChangelogManager
	 */
	private $changelogManager;

	/**
	 * @var IControlFactory
	 */
	private $addToChangelogFactory;

	/**
	 * @var Changelog
	 */
	private $changelogModel;


	public function __construct(
		ChangelogManager $changelogManager,
		AddToChangelogControlFactory $addToChangelogFactory,
	    Changelog $changelogModel)
	{
		$this->changelogManager = $changelogManager;
		$this->addToChangelogFactory = $addToChangelogFactory;
		$this->changelogModel = $changelogModel;
	}


	public function handleExecuteQueries()
	{
		$errors = $this->changelogManager->executeNewQueries();

		if (empty($errors)) {
			$this->flashMessage('All queries has been executed successfully', 'success');
			$this->redirect('Changelog:');
		}

		$this->template->errors = $errors;
	}


	public function actionDefault()
	{
		$this->template->errors = [];
		$this->changelogManager->importNewChangelogData();
		$this->template->queriesToExecute = $this->changelogModel->getQueriesToExecute();
	}


	/**
	 * @return AddToChangelog\AddToChangelogControl
	 */
	protected function createComponentAddToChangelog()
	{
		return $this->addToChangelogFactory->create();
	}

}

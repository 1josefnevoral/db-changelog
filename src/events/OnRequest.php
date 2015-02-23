<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Events;

use Kdyby\Events\Subscriber;
use Lovec;
use Lovec\DbChangelog\App\Presenters\ChangelogPresenter;
use Lovec\DbChangelog\ChangelogManager;
use Nette;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Tracy\Debugger;


/**
 * Redirects to changelog presenter if detect new migrations
 * that haven't been executed yet.
 */
class OnRequest implements Subscriber
{

	/**
	 * @var ChangelogManager
	 */
	private $changelogManager;

	/**
	 * @var \Nette\Http\Request
	 */
	private $httpRequest;

	/**
	 * @var Nette\Http\Response
	 */
	private $httpResponse;


	public function __construct(ChangelogManager $changelogManager, Nette\Http\Request $httpRequest, Nette\Http\Response $httpResponse)
	{
		$this->changelogManager = $changelogManager;
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
	}


	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array('Nette\Application\Application::onPresenter');
	}


	public function onPresenter(Application $app, Presenter $presenter)
	{
		if (Debugger::$productionMode === FALSE
			&& $this->changelogManager->haveFilesChanged()
			&& $this->changelogManager->importNewChangelogData()
			&& ! $presenter instanceof ChangelogPresenter
		) {
			$this->httpResponse->redirect('db-changelog');
			exit();
		}
	}

}

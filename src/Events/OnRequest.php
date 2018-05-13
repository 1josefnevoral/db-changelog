<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file LICENSE that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Events;

use Lovec;
use Lovec\DbChangelog\App\Presenters\ChangelogPresenter;
use Lovec\DbChangelog\ChangelogManager;
use Nette;
use Nette\Application\Application;
use Nette\Application\IPresenter;
use Nette\Http\Request;
use Nette\Http\Response;
use Tracy\Debugger;


/**
 * Redirects to changelog presenter if detect new migrations
 * that haven't been executed yet.
 */
class OnRequest
{

	/**
	 * @var ChangelogManager
	 */
	private $changelogManager;

	/**
	 * @var Request
	 */
	private $httpRequest;

	/**
	 * @var Response
	 */
	private $httpResponse;


	public function __construct(ChangelogManager $changelogManager, Request $httpRequest, Response $httpResponse)
	{
		$this->changelogManager = $changelogManager;
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
	}


	public function onRequest(Application $application, IPresenter $presenter)
	{
		if (
			Debugger::$productionMode === FALSE
			&& $this->changelogManager->haveFilesChanged()
			&& $this->changelogManager->importNewChangelogData()
			&& ! $presenter instanceof ChangelogPresenter
		) {
			$this->httpResponse->redirect('/db-changelog');
			exit();
		}
	}

}

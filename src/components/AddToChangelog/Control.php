<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Components\AddToChangelog;

use Lovec\DbChangelog\ChangelogManager;
use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;


class Control extends Nette\Application\UI\Control
{

	/**
	 * @var ChangelogManager
	 */
	private $changelogManager;


	public function __construct(ChangelogManager $changelogManager)
	{
		$this->changelogManager = $changelogManager;
	}


	protected function createComponentForm()
	{
		$form = new Form;
		$form->setRenderer(new Bs3FormRenderer);
		$form->addText('description', 'Short description')
			->setRequired('Write short description what you are changing');
		$form->addTextArea('queries', 'SQL queries')
			->setRequired('Complete your query')
			->setAttribute('rows', 10);
		$form->addSubmit('send', 'Save')
			->setAttribute('class', 'btn btn-success');
		$form->onSuccess[] = $this->processForm;
		return $form;
	}


	public function processForm($form, $values)
	{
		$this->changelogManager->addNewQueries($values->description, $values->queries);
		$this->flashMessage('Queries saved');
		$this->presenter->redirect('Changelog:add');
	}


	public function render()
	{
		$this['form']->render();
	}

}

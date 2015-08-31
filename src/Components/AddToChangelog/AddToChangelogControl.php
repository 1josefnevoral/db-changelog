<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file LICENSE that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Components\AddToChangelog;

use Lovec\DbChangelog\ChangelogManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Nextras\Forms\Rendering\Bs3FormRenderer;


class AddToChangelogControl extends Control
{

	/**
	 * @var ChangelogManager
	 */
	private $changelogManager;


	public function __construct(ChangelogManager $changelogManager)
	{
		$this->changelogManager = $changelogManager;
	}


	/**
	 * @return Form
	 */
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


	public function processForm(Form $form, ArrayHash $values)
	{
		$this->changelogManager->addNewQueries($values->description, $values->queries);
		$this->presenter->flashMessage('Queries saved');
		$this->presenter->redirect('Changelog:add');
	}


	public function render()
	{
		/** @var Form[] $this */
		$this['form']->render();
	}

}

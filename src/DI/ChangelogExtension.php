<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file LICENSE that was distributed with this source code.
 */

namespace Lovec\DbChangelog\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;
use Nette\Utils\AssertionException;
use Lovec\DbChangelog\ChangelogManager;
use Lovec\DbChangelog\Components\AddToChangelog\AddToChangelogControlFactory;
use Lovec\DbChangelog\Events\OnRequest;
use Lovec\DbChangelog\Model\Changelog;
use Lovec\DbChangelog\Router\RouterFactory;

class ChangelogExtension extends CompilerExtension
{
	/**
	 * @var array
	 */
	private $defaults = [
		'dir' => '%appDir%/changelog',
		'table' => 'changelog'
	];


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$this->validateConfigParameters($config);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('manager'))
			->setClass(ChangelogManager::class)
			->setArguments([$config['dir']]);

		$builder->addDefinition($this->prefix('model'))
			->setClass(Changelog::class)
			->setArguments([$config['table']]);

		$builder->addDefinition($this->prefix('component.add'))
			->setImplement(AddToChangelogControlFactory::class);

		$builder->addDefinition($this->prefix('event.onRequest'))
			->setClass(OnRequest::class);

		$builder->addDefinition($this->prefix('routerService'))
			->setClass(RouterFactory::class)
			->setInject(TRUE);

		$builder->addDefinition($this->prefix('routerServiceFactory'))
			->setFactory($this->prefix('@routerService') . '::createRouter')
			->setAutowired(FALSE);

		$builder->getDefinition('router')
			->addSetup('offsetSet', [NULL, $this->prefix('@routerServiceFactory')]);

		$builder->getDefinition('application.application')
			->addSetup('$onPresenter[]', [['@Lovec\DbChangelog\Events\OnRequest', 'onRequest']]);

		$builder->getDefinition('nette.presenterFactory')
			->addSetup('setMapping', [$this->getPresenterMapping()]);
	}


	/**
	 * @return array
	 */
	private function getPresenterMapping()
	{
		return ['DbChangelog' => 'Lovec\DbChangelog\App\Presenters\*Presenter'];
	}


	/**
	 * @param array
	 * @throws AssertionException
	 * @throws \Exception
	 */
	private function validateConfigParameters($config)
	{
		Validators::assertField($config, 'dir', 'string');
		Validators::assertField($config, 'table', 'string');

		if ( ! is_dir($config['dir'])) {
			throw new \Exception('Dir "' . $config['dir'] . '" not found! Create it.');
		}
		if ( ! is_writeable($config['dir'])) {
			throw new \Exception('Dir "' . $config['dir'] . '" is not writeable.');
		}
	}

}

<?php

declare(strict_types = 1);

namespace App;

use Drago\Parameters\Environment;
use Nette\Application\UI\Presenter;


/**
 * Base class for all modules.
 */
abstract class BasePresenter extends Presenter
{
	/**
	 * @var Environment
	 * @inject
	 */
	public $environment;


	protected function startup(): void
	{
		parent::startup();
		$mode = $this->environment->isProduction();
		$mode ? $this->setLayout('layout') : $this->setLayout('dev');
	}
}

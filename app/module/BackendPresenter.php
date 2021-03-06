<?php

declare(strict_types=1);

namespace Base;

use Drago\Authorization\Control\AuthorizationControl;
use Nette\Application\AbortException;


class BackendPresenter extends BasePresenter
{
	use AuthorizationControl;

	/**
	 * @throws AbortException
	 */
	protected function startup(): void
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Admin:Sign:in');
		}
	}


	protected function beforeRender(): void
	{
		$this->template->module = $this->getName() . ':' . $this->getView();
	}
}

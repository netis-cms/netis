<?php

declare(strict_types=1);

namespace Base;

use Drago\Authorization\Control\Authorization;
use Nette\Application\AbortException;


class BackendPresenter extends BasePresenter
{
	use Authorization;

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
}

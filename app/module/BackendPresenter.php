<?php

declare(strict_types=1);

namespace Base;

use Nette\Application\AbortException;


class BackendPresenter extends BasePresenter
{
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

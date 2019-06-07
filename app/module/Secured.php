<?php

declare(strict_types = 1);

namespace App;

/**
 * Dashboard secured class for admin module.
 */
class SecuredPresenter extends DashboardPresenter
{
	protected function startup(): void
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Admin:Sign:in');
		}
	}
}

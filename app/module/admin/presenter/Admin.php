<?php

declare(strict_types = 1);

namespace Module\Admin;

use App\SecuredPresenter;


final class AdminPresenter extends SecuredPresenter
{
	protected function startup(): void
	{
		parent::startup();
		$this->setTemplate('admin', 'dev.admin');
	}
}

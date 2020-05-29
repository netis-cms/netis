<?php

namespace Base;


class Backend extends Base
{
	protected function startup(): void
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Admin:Sign:in');
		}
	}
}

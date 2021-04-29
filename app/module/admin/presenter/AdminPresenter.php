<?php

declare(strict_types=1);

namespace Module\Admin;

use Base\BackendPresenter;


final class AdminPresenter extends BackendPresenter
{
	protected function beforeRender(): void
	{
		$this->template->module = $this->getName() . ':' . $this->getView();
	}
}

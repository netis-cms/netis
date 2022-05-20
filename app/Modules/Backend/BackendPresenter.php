<?php

declare(strict_types=1);

namespace App\Modules\Backend;

use App\Modules\BasePresenter;
use Drago\Authorization\Control\AuthorizationControl;
use Nette\Application\AbortException;


/**
 * @property-read BackendTemplate $template
 */
abstract class BackendPresenter extends BasePresenter
{
	use AuthorizationControl;

	/**
	 * @throws AbortException
	 */
	protected function startup(): void
	{
		parent::startup();
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect(':Backend:Sign:in');
		}
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->setLayout(__DIR__ . '/Admin/templates/@layout.latte');
		$this->template->widgetPath = __DIR__ . '/../../UI/Widgets/';
	}
}

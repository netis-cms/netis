<?php

declare(strict_types=1);

namespace App\Modules\Backend;

use App\Modules\Backend\Sign\User;
use App\Modules\BasePresenter;
use Drago\Authorization\Control\AuthorizationControl;
use Nette\Application\AbortException;
use Nette\DI\Attributes\Inject;


/**
 * @property-read BackendTemplate $template
 */
abstract class BackendPresenter extends BasePresenter
{
	use AuthorizationControl;

	#[Inject]
	public User $user;


	/**
	 * @throws AbortException
	 */
	protected function startup(): void
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Backend:Sign:in');
		}
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->setLayout(__DIR__ . '/Admin/templates/@layout.latte');
		$this->template->widgetPath = __DIR__ . '/../../UI/Widgets/';
		$this->template->user = $this->user;
	}
}

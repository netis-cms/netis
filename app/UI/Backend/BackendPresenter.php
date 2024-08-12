<?php

declare(strict_types=1);

namespace App\UI\Backend;

use App\Core\User\User;
use App\UI\Presenter;
use Drago\Authorization\Control\AuthorizationControl;
use Nette\DI\Attributes\Inject;


abstract class BackendPresenter extends Presenter
{
	use AuthorizationControl;

	#[Inject]
	public User $user;


	protected function startup(): void
	{
		parent::startup();
		$user = $this->getUser();
		if ($user->isLoggedIn()) {
			return;

		} elseif ($user->getLogoutReason() === $user::LogoutInactivity) {
			$this->flashMessage('You have been signed out due to inactivity. Please sign in again.');
			$this->redirect('Sign:in', ['backlink' => $this->storeRequest()]);

		} else {
			$this->redirect('Sign:in');
		}
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->user = $this->user;
	}
}

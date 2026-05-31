<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Nette\Application\UI\Presenter;
use Nette\Security\User;


/** Ensures the user is logged in. */
trait RequireLogged
{
	/** Injects require logged user. */
	public function injectRequireLoggedUser(Presenter $presenter, User $user): void
	{
		$presenter->onStartup[] = function () use ($presenter, $user) {
			if ($user->isLoggedIn()) {
				return;
			}

			if ($user->getLogoutReason() === $user::LogoutInactivity) {
				$presenter->flashMessage('You have been signed out due to inactivity. Please sign in again.');
				$presenter->redirect(':Backend:Sign:in', ['backlink' => $presenter->storeRequest()]);
			} else {
				$presenter->redirect(':Backend:Sign:in');
			}
		};
	}
}

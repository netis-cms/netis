<?php

declare(strict_types=1);

namespace App\Core\User;

use Nette\Application\UI\Presenter;


trait UserRequireLogged
{
	public function injectRequireLoggedUser(Presenter $presenter, User $user): void
	{
		$presenter->onStartup[] = function () use ($presenter, $user) {
			if ($user->isLoggedIn()) {
				return;

			} elseif ($user->getLogoutReason() === $user::LogoutInactivity) {
				$presenter->flashMessage('You have been signed out due to inactivity. Please sign in again.');
				$presenter->redirect(':Backend:Sign:in', ['backlink' => $presenter->storeRequest()]);

			} else {
				$presenter->redirect(':Backend:Sign:in');
			}
		};
	}
}

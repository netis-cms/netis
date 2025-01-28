<?php

declare(strict_types=1);

namespace App\UI\Backend;

use App\Core\User\User;
use App\Core\User\UserRequireLogged;
use App\UI\Presenter;
use Nette\DI\Attributes\Inject;


/**
 * Abstract class for backend presenters.
 * This class ensures the user is logged in and provides user data to the template.
 */
abstract class BackendPresenter extends Presenter
{
	// Trait to enforce user is logged in
	use UserRequireLogged;

	// Inject the User object to access current user data
	#[Inject]
	public User $user;


	/**
	 * Runs before rendering the page.
	 * Sets the current user to the template.
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		// Ensure the user is set and accessible in the template
		$this->template->user = $this->user ?? null; // Safe access to user object
	}
}

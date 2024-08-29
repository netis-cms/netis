<?php

declare(strict_types=1);

namespace App\UI\Backend;

use App\Core\User\User;
use App\Core\User\UserRequireLogged;
use App\UI\Presenter;
use Nette\DI\Attributes\Inject;


abstract class BackendPresenter extends Presenter
{
	use UserRequireLogged;

	#[Inject]
	public User $user;


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->user = $this->user;
	}
}

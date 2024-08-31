<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\User\User;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;


class Factory
{
	public function __construct(
		private readonly Translator $translator,
		private readonly User $user,
	) {
	}


	public function create(): Form
	{
		$form = new Form();
		if ($this->user->isLoggedIn()) {
			$form->addProtection();
		}
		$form->setTranslator($this->translator);
		return $form;
	}
}

<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\User\User;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;


/**
 * Factory class to create forms with optional protection based on user login status.
 */
readonly class Factory
{
	/**
	 * Constructor to initialize dependencies.
	 *
	 * @param Translator $translator Translator for form translation.
	 * @param User $user User object to check login status.
	 */
	public function __construct(
		private Translator $translator,
		private User $user,
	) {
	}


	/**
	 * Creates and returns a form instance.
	 *
	 * If the user is logged in, adds protection to the form.
	 * Sets the translator for form elements.
	 *
	 * @return Form The created form instance.
	 */
	public function create(): Form
	{
		$form = new Form();

		// Add form protection if the user is logged in
		if ($this->user->isLoggedIn()) {
			$form->addProtection();
		}

		// Set the translator for form
		$form->setTranslator($this->translator);

		return $form;
	}
}

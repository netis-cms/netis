<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\UI\Backend\Sign\User\UserEntity;
use Dibi\Connection;
use Dibi\UniqueConstraintViolationException;
use Drago\Form\Autocomplete;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Utils\AssertionException;
use Nette\Utils\Random;
use Nette\Utils\Validators;


/** Factory for creating user registration forms. */
readonly class SignUpFactory
{
	public function __construct(
		private Passwords $password,
		private Factory $factory,
		private Connection $connection,
	) {
	}


	/** Creates the user registration form. */
	public function create(): Form
	{
		$form = $this->factory->create();
		$form->addTextInput(SignUpValues::Username, 'Username')
			->setRequired('Please enter your full name.')
			->setPlaceholder('Full name')
			->setAutocomplete(Autocomplete::Name);

		$form->addEmailField()
			->setDefaultValue('@');

		$form->addPasswordField()
			->setAutocomplete(Autocomplete::NewPassword)
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 8)
			->addRule(
				$form::Pattern,
				'The password must contain uppercase and lowercase letters, numbers, and a special character.',
				'^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[^A-Za-z0-9])[\S]{8,}$',
			);

		$form->addPasswordConfirmationField()
			->setAutocomplete(Autocomplete::Off);

		$form->addSubmit('send', 'Sign up');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	/**
	 * Handles the successful submission of the form.
	 * @throws Exception
	 * @throws AssertionException
	 */
	public function success(Form $form, SignUpValues $values): void
	{
		$values->password = $this->password->hash($values->password);
		$values->token = Random::generate(32);
		$values->offsetUnset(SignUpValues::Verify);

		Validators::assert($values->email, 'email');

		try {
			$this->connection->insert(UserEntity::Table, $values->toArray())
				->execute();

		} catch (UniqueConstraintViolationException $e) {
			$message = match ($e->getCode()) {
				1062 => "We're sorry, but an account with this email address already exists.",
				default => 'Unknown status code.',
			};
			$form->addError($message);
		}
	}
}

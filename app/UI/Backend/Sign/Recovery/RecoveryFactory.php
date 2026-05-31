<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign\Recovery;

use App\UI\Backend\Sign\Factory;
use App\UI\Backend\Sign\User\UserRepository;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Form\Autocomplete;
use Drago\Localization\Translator;
use Nette\Application\UI\Form;
use Nette\Forms\Control;
use Nette\Security\Passwords;


/** Factory for creating password recovery forms and handling password recovery logic. */
class RecoveryFactory
{
	public Translator $translator;


	public function __construct(
		private readonly Factory $factory,
		private readonly SessionService $sessionService,
		private readonly UserRepository $userRepository,
		private readonly EmailService $emailService,
		private readonly Passwords $passwords,
	) {
	}


	/** Creates the password recovery request form. */
	public function createRequest(): Form
	{
		$form = $this->factory->create();
		$form->addEmailField()
			->addRule([$this, 'emailCheck'], "We're sorry, but we don't know such an email address.");

		$form->addSubmit('send', 'Reset password');
		$form->onSuccess[] = $this->request(...);
		return $form;
	}


	/** Creates the form for checking the recovery token. */
	public function createCheckToken(): Form
	{
		$form = $this->factory->create();
		$form->addTextInput('token', 'Code')
			->addRule([$this, 'tokenCheck'], 'The code entered is invalid.')
			->setPlaceholder('Enter the code from the email')
			->setRequired('Please enter the code from the email.')
			->setAutocomplete(Autocomplete::Off);

		$form->addSubmit('send', 'Continue password recovery');
		$form->onSuccess[] = $this->checkToken(...);
		return $form;
	}


	/** Checks if the entered token is valid. */
	public function tokenCheck(Control $input): bool
	{
		return $this->sessionService
			->isTokenValid($input->getValue());
	}


	/**
	 * Checks if the email address exists.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function emailCheck(Control $input): bool
	{
		$findEmail = $this->userRepository->findUserByEmail($input->getValue());
		return (bool) $findEmail;
	}


	/** Creates the form for changing the password. */
	public function createChangePassword(): Form
	{
		$form = $this->factory->create();
		$form->addPasswordField()
			->setAutocomplete(Autocomplete::NewPassword);

		$form->addPasswordConfirmationField()
			->setAutocomplete(Autocomplete::Off);

		$form->addSubmit('send', 'Change your password');
		$form->onSuccess[] = $this->changePassword(...);
		return $form;
	}


	/** Handles the password recovery request form submission. */
	public function request(Form $form): void
	{
		try {
			$values = $form->getValues();
			$email = (string) $values['email'];

			$token = $this->sessionService->generateToken($email);
			$request = $this->emailService;
			$request->setTranslator($this->translator);
			$request->sendEmail($email, $token);

		} catch (\Throwable $e) {
			$message = 'Unknown status code.';
			$form->addError($message);
		}
	}


	/** Handles the token check form submission. */
	public function checkToken(): void
	{
		$this->sessionService
			->setTokenCheck();
	}


	/** Handles the password change form submission. */
	public function changePassword(Form $form): void
	{
		try {
			$password = (string) $form->getValues()['password'];
			$email = $this->sessionService->getEmail();
			$user = $this->userRepository->getUserByEmail($email);

			$user->password = $this->passwords->hash($password);
			$this->userRepository->save($user);
			$this->sessionService->removeToken();

		} catch (\Throwable $e) {
			$message = match ($e->getCode()) {
				101 => 'User with email was not found.',
				default => 'Unknown status code.',
			};
			$form->addError($message);
		}
	}
}

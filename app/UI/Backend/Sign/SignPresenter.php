<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\UI\Backend\Sign\Recovery\RecoveryFactory;
use App\UI\Backend\Sign\Recovery\SessionService;
use App\UI\BasePresenter;
use Drago\Application\UI\Alert;
use Drago\Form\Autocomplete;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Neon\Exception;
use Nette\Security\AuthenticationException;
use Throwable;
use Tracy\Debugger;


/**
 * Handles user authentication and registration.
 * @property-read SignTemplate $template
 */
final class SignPresenter extends BasePresenter
{
	#[Persistent]
	public string $backlink = '';


	public function __construct(
		private readonly Factory $factory,
		private readonly SignUpFactory $signUpFactory,
		private readonly RecoveryFactory $recoveryFactory,
		private readonly SessionService $sessionService,
	) {
		parent::__construct();
	}


	private function redrawSnippets(): void
	{
		$this->redrawControl('title');
		$this->redrawControl('body');
	}


	/** Sets the recovery token and redraw snippets if AJAX. */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		if ($this->getAction() === 'recovery') {
			$this->template->signRecoveryToken = $this->sessionService->createSignRecoveryToken();
		}

		if ($this->isAjax()) {
			$this->redrawSnippets();
		}
	}


	/** Creates and handles the sign-in form. */
	protected function createComponentSignIn(): Form
	{
		$form = $this->factory->create();
		$form->addEmailField();
		$form->addPasswordField()
			->setAutocomplete(Autocomplete::CurrentPassword);

		$form->addSubmit('send', 'Sign in');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	/** Handles sign-in form success. */
	public function success(Form $form, SignValues $values): void
	{
		try {
			$this->getUser()->login($values->email, $values->password);
			$this->restoreRequest($this->backlink);
			$this->redirect(':Backend:Admin:');
		} catch (AuthenticationException $e) {
			$messages = [
				1 => 'User not found.',
				2 => 'The password is incorrect.',
			];
			$form->addError($messages[$e->getCode()] ?? 'Unknown error occurred.');
		}
	}


	/** Creates and handles the sign-up form. */
	protected function createComponentSignUp(): Form
	{
		$form = $this->signUpFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Your registration has been successfully completed, you can now log in.', Alert::Success);
			$this->redirect('in');
		};
		return $form;
	}


	/**
	 * Creates and handles the password recovery request form.
	 * @throws Exception
	 * @throws Throwable
	 */
	protected function createComponentSignRecoveryRequest(): Form
	{
		$factory = $this->recoveryFactory;
		$factory->translator = $this->getTranslator();

		$form = $factory->createRequest();
		$form->onSuccess[] = function () {
			$this->flashMessage('A password recovery code has been sent to your email.', Alert::Success);
		};

		$form->onError[] = function (Form $form) {
			foreach ($form->getErrors() as $error) {
				Debugger::barDump($error);
			}
		};
		return $form;
	}


	/** Creates and handles the token check form for password recovery. */
	protected function createComponentSignRecoveryCheckToken(): Form
	{
		$form = $this->recoveryFactory->createCheckToken();
		$form->onSuccess[] = function () {
			$this->flashMessage('Code check was successful.', Alert::Success);
		};
		return $form;
	}


	/** Creates and handles the password change form. */
	protected function createComponentSignRecoveryChangePassword(): Form
	{
		$form = $this->recoveryFactory->createChangePassword();
		$form->onSuccess[] = function () {
			$this->flashMessage('Password change was successful.', Alert::Success);
			$this->redirect('in');
		};
		return $form;
	}


	/** Logs out the current user. */
	public function actionOut(): void
	{
		$this->getUser()->logout();
	}
}

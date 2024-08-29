<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use App\Core\Settings\SettingsRequire;
use App\Core\User\UserSingUpFactory;
use Drago\Application\UI\Alert;
use Drago\Localization\TranslatorAdapter;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\AbortException;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;


/**
 * Sing-in user.
 * @property SignTemplate $template
 */
final class SignPresenter extends Presenter
{
	use TranslatorAdapter;
	use SettingsRequire;

	#[Persistent]
	public string $backlink = '';


	public function __construct(
		private readonly Factory $factory,
		private readonly UserSingUpFactory $userSingUpFactory,
	) {
		parent::__construct();
	}


	/**
	 * @throws Exception
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect(':Backend:Admin:');
		}
	}


	protected function createComponentSignIn(): Form
	{
		$form = $this->factory->create();
		$form->addText(SignData::Email, 'Email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'Email address')
			->addRule($form::Email)
			->setRequired();

		$form->addPassword(SignData::Password, 'Password')
			->setHtmlAttribute('placeholder', 'Your password')
			->setRequired();

		$form->addSubmit('send', 'Sign in');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws AbortException
	 */
	public function success(Form $form, SignData $data): void
	{
		try {
			$this->getUser()->login($data->email, $data->password);
			$this->restoreRequest($this->backlink);
			$this->redirect(':Backend:Admin:');

		} catch (AuthenticationException $e) {
			if ($e->getCode()) {
				$message = match ($e->getCode()) {
					1 => 'User not found.',
					2 => 'The password is incorrect.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}
		}
	}


	protected function createComponentSignUp(): Form
	{
		$form = $this->userSingUpFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Registration was successful.', Alert::Info);
			$this->redirect('in');
		};
		return $form;
	}


	/**
	 * Logout user from application.
	 * @throws AbortException
	 */
	#[NoReturn] public function actionUserOut(): void
	{
		$this->getUser()->logout();
		$this->redirect('in');
	}
}

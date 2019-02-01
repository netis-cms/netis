<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Module\Admin;

use Base;
use Drago;
use Supplement;

use Nette\Application\UI;
use Nette\Security;

/**
 * Sing-in user.
 */
final class SignPresenter extends Base\BasePresenter
{
	/**
	 * @var Supplement\Gravatar
	 * @inject
	 */
	public $gravatar;

	/**
	 * @return Drago\Localization\Translator
	 */
	public function translator()
	{
		parent::translator();
		$path = __DIR__ . '/../locale/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$user = $this->user->identity;
		if ($user) {
			$welcome  = 'login.welcome.back';
			$gravatar = $user->data['email'];
		}
		$this->template->form = $this['signIn'];
		$this->template->welcome  = isset($welcome) ? $welcome : 'login.welcome';
		$this->template->gravatar = $this->gravatar->getGravatar(isset($gravatar) ? $gravatar : null, 100);
	}

	/**
	 * @return UI\Form
	 */
	protected function createComponentSignIn()
	{
		$form = $this->createForm();
		$form->setTranslator($this->translator());

		$form->addText('email', 'form.email')
			->setType('email')
			->setRequired('form.required')
			->setAttribute('placeholder', 'form.email.full')
			->addRule(UI\Form::EMAIL, 'form.email.rule');

		$form->addPassword('password', 'form.password')
			->setAttribute('placeholder', 'form.password.full')
			->setRequired('form.required');

		$form->addSubmit('send', 'form.send.login');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}

	public function success(UI\Form $form, $values)
	{
		try {
			$this->user->login($values->email, $values->password);
			$this->redirect(':Admin:Admin:');

		} catch (Security\AuthenticationException $e) {
			switch ($e->getCode()) {
				case 1: $message = 'form.user.error'; break;
				case 2: $message = 'form.password.error'; break;
			}
			$form->addError($message);
			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}

	/**
	 * Logout user.
	 */
	public function actionOut()
	{
		$this->user->logout();
		$this->redirect(':Admin:Sign:in');
	}

}

<?php

declare(strict_types=1);

namespace Module\Install\Control;

use App\Entity\UsersEntity;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Authorization\Entity\UsersRolesEntity;
use Drago\Localization\Translator;
use Drago\Utils\ExtraArrayHash;
use Module\Install\Service\Steps;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\InvalidStateException;
use Nette\Security\Passwords;


/**
 * Add administrator account.
 */
final class AccountControl extends Control
{
	public function __construct(
		private Translator $translator,
		private Connection $db,
		private Steps $steps,
		private Passwords $password,
	) {
	}


	public function render(): void
	{
		if ($this->template instanceof Template) {
			$template = $this->template;
			$template->setFile(__DIR__ . '/../templates/Control.account.latte');
			$template->setTranslator($this->translator);
			$template->form = $this['account'];
			$template->render();
		} else {
			throw new InvalidStateException('Control is without template.');
		}
	}


	public function createComponentAccount(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->addText('username', 'form.name.acc')
			->setRequired('form.required');

		$form->addText('email', 'form.email')
			->setDefaultValue('@')
			->setHtmlType('email')
			->setRequired('form.required')
			->addRule(Form::EMAIL, 'form.email.rule');

		$form->addPassword('password', 'form.password')
			->setRequired('form.required')
			->addRule(Form::MIN_LENGTH, 'form.password.rule', 6);

		$form->addPassword('verify', 'form.password.verify')
			->setRequired('form.required')
			->addRule(Form::EQUAL, 'form.password.verify.rule', $form['password']);

		$form->addSubmit('send', 'form.send.acc');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Exception
	 */
	public function success(Form $form, ExtraArrayHash $data): void
	{
		$data->password = $this->password->hash($data->password);
		$data->offsetUnset('verify');

		// Insert records into the database.
		$this->db->insert(UsersEntity::TABLE, $data->toArray())->execute();
		$this->db->insert(UsersRolesEntity::TABLE, [
			UsersRolesEntity::USER_ID => 1,
			UsersRolesEntity::ROLE_ID => 3,
		])->execute();

		// Save the installation step.
		$this->steps->cache->save(Steps::STEP, ['step' => 5]);
		$this->presenter->flashMessage('message.acc', 'success');
	}
}

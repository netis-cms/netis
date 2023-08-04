<?php

declare(strict_types=1);

namespace App\Modules\Install;

use App\Modules\Backend\Sign\UsersEntity;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Application\UI\ExtraTemplate;
use Drago\Authorization\Control\Access\UsersRolesEntity;
use Drago\Localization\Translator;
use Drago\Utils\ExtraArrayHash;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Utils\Random;


/**
 * Add administrator account.
 */
final class AccountFactory
{
	public function __construct(
		private readonly Connection $db,
		private readonly Steps $steps,
		private readonly Passwords $password,
		private readonly Translator $translator,
	) {
	}


	public function create(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->addText('username', 'Username')
			->setRequired();

		$form->addText('email', 'Email address')
			->setDefaultValue('@')
			->setHtmlType('email')
			->setRequired()
			->addRule($form::Email);

		$form->addPassword('password', 'Password')
			->setRequired()
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 6);

		$form->addPassword('verify', 'Password to check')
			->setRequired()
			->addRule($form::Equal, 'Passwords do not match.', $form['password']);

		$form->addSubmit('send', 'Register');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Exception
	 */
	public function success(Form $form, ExtraArrayHash $data): void
	{
		$data->offsetSet('token', Random::generate(60));
		$data->password = $this->password->hash($data->password);
		$data->offsetUnset('verify');

		// Insert records into the database.
		$this->db->insert(UsersEntity::TABLE, $data->toArray())->execute();
		$this->db->insert(UsersRolesEntity::TABLE, [
			UsersRolesEntity::USER_ID => 1,
			UsersRolesEntity::ROLE_ID => 3,
		])->execute();

		// Save the installation step.
		$this->steps->setStep(5);
	}
}

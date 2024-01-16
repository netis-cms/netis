<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;

use App\Modules\BaseFactory;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Authorization\Control\Access\AccessRolesEntity;
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
		private readonly Passwords $password,
		private readonly BaseFactory $baseFactory,
	) {
	}


	public function create(): Form
	{
		$form = $this->baseFactory->create();
		$form->addText(AccountData::Username, 'Username')
			->setRequired();

		$form->addText(AccountData::Email, 'Email address')
			->setDefaultValue('@')
			->setHtmlType('email')
			->addRule($form::Email)
			->setRequired();

		$form->addPassword(AccountData::Password, 'Password')
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 6)
			->setRequired();

		$form->addPassword(AccountData::Verify, 'Password to check')
			->addRule($form::Equal, 'Passwords do not match.', $form['password'])
			->setRequired();

		$form->addSubmit('send', 'Register');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Exception
	 */
	public function success(Form $form, AccountData $data): void
	{
		$data->password = $this->password->hash($data->password);
		$data->token = Random::generate(60);
		$data->offsetUnset(AccountData::Verify);

		// Insert records into the database.
		$this->db->insert(UsersEntity::Table, $data->toArray())->execute();
		$this->db->insert(AccessRolesEntity::Table, [
			AccessRolesEntity::UserId => $this->db->getInsertId(),
			AccessRolesEntity::RoleId => 2,
		])->execute();
	}
}

<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;

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
		private readonly AccountForm $accountForm,
	) {
	}


	public function create(): Form
	{
		$form = $this->accountForm->create();
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
			AccessRolesEntity::ColumnUserId => $this->db->getInsertId(),
			AccessRolesEntity::ColumnRoleId => 2,
		])->execute();
	}
}

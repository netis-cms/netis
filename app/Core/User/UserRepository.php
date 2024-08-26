<?php

declare(strict_types=1);

namespace App\Core\User;

use Dibi\Connection;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\From;
use Drago\Authorization\Conf;
use Drago\Authorization\Control\Access\AccessRolesViewEntity;
use Drago\Authorization\Tracy\PanelCookie;
use Drago\Database\Database;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IdentityHandler;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;


#[From(UsersEntity::Table, UsersEntity::PrimaryKey, class: UsersEntity::class)]
class UserRepository extends Database implements Authenticator, IdentityHandler
{
	public function __construct(
		protected Connection $db,
		private readonly Passwords $password,
		private readonly PanelCookie $panelCookie,
	) {
		parent::__construct($db);
	}


	/**
	 * @throws AuthenticationException
	 * @throws Exception|AttributeDetectionException
	 */
	public function authenticate(string $user, string $password): SimpleIdentity
	{
		// Find user.
		$user = $this->findUser($user);

		// User not found.
		if (!$user) {
			throw new AuthenticationException('User not found.', self::IdentityNotFound);

		// Invalid password.
		} elseif (!$this->password->verify($password, $user->password)) {
			throw new AuthenticationException('The password is incorrect.', self::InvalidCredential);


		// Re-hash password.
		} elseif ($this->password->needsRehash($user->password)) {
			$user->password = $this->password->hash($password);
			$this->save($user->toArray());

		}
		$user->offsetUnset('password');
		return new SimpleIdentity($user->id, $this->findUserRoles($user->id), $user);
	}


	public function sleepIdentity(UserToken|IIdentity $identity): SimpleIdentity
	{
		return new SimpleIdentity($identity->token);
	}


	/**
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function wakeupIdentity(IIdentity $identity): ?SimpleIdentity
	{
		$user = $this->findUserById($identity->getId());
		if ($user === null) {
			return null;
		}

		$role = $this->findUserRoles($user->id);
		if (in_array(Conf::RoleAdmin, $role, true)) {
			if (!$this->panelCookie->load()) {
				$this->panelCookie->save($role);
			}
		}

		if ($this->panelCookie->load()) {
			$role = $this->panelCookie->load();
		}

		return new SimpleIdentity($user->id, $role, $user);
	}


	/**
	 * Find user by email.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	private function findUser(string $user): array|UsersEntity|null
	{
		return $this->find(UsersEntity::ColumnEmail, $user)
			->record();
	}


	/**
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function findUserById(string $id): array|UsersEntity|null
	{
		return $this->find(UsersEntity::ColumnToken, $id)
			->record();
	}


	/**
	 * Find user roles.
	 */
	private function findUserRoles(int $userId): array|string
	{
		return $this->getConnection()
			->select('*')->from(AccessRolesViewEntity::Table)
			->where(AccessRolesViewEntity::ColumnUserId, '= ?', $userId)
			->fetchPairs(null, AccessRolesViewEntity::ColumnRole) ?: Conf::RoleMember;
	}
}

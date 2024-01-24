<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;

use Dibi\Connection;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Authorization\Conf;
use Drago\Authorization\Control\Access\AccessRolesViewEntity;
use Drago\Authorization\Tracy\PanelCookie;
use Drago\Database\Repository;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IdentityHandler;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;


#[Table(UsersEntity::Table, UsersEntity::PrimaryKey)]
class UserRepository implements Authenticator, IdentityHandler
{
	use Repository;

	public function __construct(
		protected Connection $db,
		private readonly Passwords $password,
		private readonly PanelCookie $panelCookie,
	) {
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
			throw new AuthenticationException('User not found.', self::IDENTITY_NOT_FOUND);

		// Invalid password.
		} elseif (!$this->password->verify($password, $user->password)) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);


		// Re-hash password.
		} elseif ($this->password->needsRehash($user->password)) {
			$user->password = $this->password->hash($password);
			$this->put($user->toArray());

		}
		$user->offsetUnset('password');
		return new SimpleIdentity($user->id, $this->findUserRoles($user->id), $user);
	}


	public function sleepIdentity(Identity|IIdentity $identity): SimpleIdentity
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
		return $this->query(UsersEntity::ColumnEmail, $user)
			->execute()->setRowClass(UsersEntity::class)
			->fetch();
	}


	/**
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function findUserById(string $id): array|UsersEntity|null
	{
		return $this->query(UsersEntity::ColumnToken, $id)
			->execute()->setRowClass(UsersEntity::class)
			->fetch();
	}


	/**
	 * Find user roles.
	 */
	private function findUserRoles(int $userId): array|string
	{
		return $this->db->select('*')->from(AccessRolesViewEntity::Table)
			->where(AccessRolesViewEntity::ColumnUserId, '= ?', $userId)
			->fetchPairs(null, AccessRolesViewEntity::ColumnRole) ?: Conf::RoleMember;
	}
}

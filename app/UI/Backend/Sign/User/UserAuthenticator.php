<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign\User;

use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IdentityHandler;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;


/** User authenticator. */
#[Table(UserEntity::Table, UserEntity::ColumnId, class: UserEntity::class)]
class UserAuthenticator implements Authenticator, IdentityHandler
{
	/** @phpstan-use Database<UserEntity> */
	use Database;

	public function __construct(
		private readonly Passwords $password,
		private readonly UserRepository $userRepository,
	) {
	}


	/**
	 * Authenticates the user using the username and password.
	 * @throws AuthenticationException
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function authenticate(string $username, string $password): SimpleIdentity
	{
		$user = $this->userRepository->findUserByEmail($username);

		if (!$user) {
			throw new AuthenticationException('User not found.', self::IdentityNotFound);
		}

		if (!$this->password->verify($password, $user->password)) {
			throw new AuthenticationException('Incorrect password.', self::InvalidCredential);
		}

		if ($this->password->needsRehash($user->password)) {
			$user->password = $this->password->hash($password);
			$this->save($user);
		}

		$user->offsetUnset('password');
		$roles = $this->userRepository->getRolesByUser($user->id);
		return new SimpleIdentity(id: $user->id, roles: $roles, data: $user->toArray());
	}


	/** Saves the user's identity for later use. */
	public function sleepIdentity(IIdentity $identity): SimpleIdentity
	{
		$data = $identity->getData();
		$token = $data['token'] ?? null;

		return new SimpleIdentity($token);
	}


	/**
	 * Loads the user and their role when restoring identity.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function wakeupIdentity(IIdentity $identity): ?SimpleIdentity
	{
		$user = $this->userRepository->findUserByToken(
			(string) $identity->getId(),
		);

		if ($user === null) {
			return null;
		}

		$user->offsetUnset('password');
		$roles = $this->userRepository->getRolesByUser($user->id);
		return new SimpleIdentity(id: $user->id, roles: $roles, data: $user->toArray());
	}
}

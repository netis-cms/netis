<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\SmartObject;


class AuthRepository implements Authenticator
{
	use SmartObject;

	public function __construct(
		private UsersRepository $usersRepository,
		private Passwords $password,
		private UsersRolesRepository $usersRolesRepository,
	) {
	}


	/**
	 * @throws AuthenticationException
	 * @throws Exception|AttributeDetectionException
	 */
	public function authenticate(string $email, string $password): SimpleIdentity
	{
		// Find user.
		$repository = $this->usersRepository;
		$user = $repository->find($email);

		// User not found.
		if (!$user) {
			throw new AuthenticationException('User not found.', self::IDENTITY_NOT_FOUND);

		// Invalid password.
		} elseif (!$this->password->verify($password, $user->password)) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);


		// Re-hash password.
		} elseif ($this->password->needsRehash($user->password)) {
			$user->password = $this->password->hash($password);
			$repository->put($user->toArray());
		}
		$user->offsetUnset('password');
		$roles = $this->usersRolesRepository->getUserRoles($user->id);
		return new SimpleIdentity($user->id, $roles, $user);
	}
}

<?php

declare(strict_types=1);

namespace App\Core\User;

use Nette\Security\User;


class UserAccess extends User
{
	/**
	 * Get user data from identity.
	 * @throws UserIdentityException
	 */
	public function getUserData(?string $name = null): mixed
	{
		$data = $this->getIdentity()?->getData() ?? [];

		if ($name !== null && !array_key_exists($name, $data)) {
			throw new UserIdentityException("Undefined array key \"$name\" in identity data.");
		}

		return $name !== null ? $data[$name] : $data;
	}


	/**
	 * Get user identity.
	 * @throws UserIdentityException
	 */
	public function getUserIdentity(): UserIdentity
	{
		$username = $this->getUserData('username');
		$email = $this->getUserData('email');

		if (!is_string($username) || !is_string($email)) {
			throw new UserIdentityException('User identity is incomplete.');
		}

		return new UserIdentity(username: $username, email: $email);
	}


	/** Check user privileges. */
	public function isAnyAllowed(string $resource, string ...$privileges): bool
	{
		if ($privileges === []) {
			return false;
		}

		$authorization = $this->getAuthorizator();
		foreach ($this->getRoles() as $role) {
			foreach ($privileges as $privilege) {
				if ($authorization->isAllowed($role, $resource, $privilege)) {
					return true;
				}
			}
		}

		return false;
	}
}

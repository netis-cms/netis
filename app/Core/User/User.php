<?php

declare(strict_types=1);

namespace App\Core\User;

use Nette\Security\User as NetteUser;


class User extends NetteUser
{
	/**
	 * Retrieves user data from the identity.
	 * If a name is specified, returns only the corresponding value.
	 *
	 * @throws UserIdentityException If the requested data is unavailable.
	 */
	public function getUserData(?string $name = null): mixed
	{
		$data = $this->getIdentity()?->getData() ?? [];

		if ($name && !array_key_exists($name, $data)) {
			throw new UserIdentityException("Undefined array key \"$name\" in identity data.");
		}

		return $name ? $data[$name] : $data;
	}


	/**
	 * Retrieves the UserIdentity object.
	 *
	 * @throws UserIdentityException If the identity data is incorrect.
	 */
	public function getUserIdentity(): UserIdentity
	{
		$username = $this->getUserData('username');
		$email = $this->getUserData('email');

		if (!$username || !$email) {
			throw new UserIdentityException('User identity is incomplete.');
		}

		return new UserIdentity(username: $username, email: $email);
	}


	/**
	 * Checks if the user has permission for any of the required privileges.
	 */
	public function isAnyAllowed(string $resource, array $privileges): bool
	{
		foreach ($this->getRoles() as $role) {
			foreach ($privileges as $privilege) {
				if ($this->getAuthorizator()->isAllowed($role, $resource, $privilege)) {
					return true;
				}
			}
		}
		return false;
	}
}

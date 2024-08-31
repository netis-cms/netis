<?php

declare(strict_types=1);

namespace App\Core\User;


class User extends \Nette\Security\User
{
	/**
	 * @throws UserIdentityException
	 */
	public function getUserData(string $name = null): mixed
	{
		$data = $this->getIdentity()->getData();
		if ($name) {
			if (!array_key_exists($name, $data)) {
				throw new UserIdentityException(
					'Undefined array key "' . $name . '" in identity data.',
				);
			}
			$data = $data[$name];
		}
		return $data;
	}


	/**
	 * @throws UserIdentityException
	 */
	public function getUserIdentity(): UserIdentity
	{
		return new UserIdentity(
			username: $this->getUserData('username'),
			email: $this->getUserData('email'),
		);
	}


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

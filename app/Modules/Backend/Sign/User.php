<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;


class User extends \Nette\Security\User
{
	/**
	 * @throws UserDataIdentityException
	 */
	public function getUserData(string $name = null): mixed
	{
		$data = $this->getIdentity()->getData();
		if ($name) {
			if (!array_key_exists($name, $data)) {
				throw new UserDataIdentityException(
					'Undefined array key "' . $name . '" in identity data.',
				);
			}
			$data = $data[$name];
		}
		return $data;
	}


	/**
	 * @throws UserDataIdentityException
	 */
	public function getUserIdentity(): UserIdentity
	{
		return new UserIdentity(
			username: $this->getUserData('username'),
			email: $this->getUserData('email'),
		);
	}
}

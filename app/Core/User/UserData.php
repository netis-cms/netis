<?php

declare(strict_types=1);

namespace App\Core\User;

use Drago\Utils\ExtraArrayHash;


/**
 * Class for storing user account information.
 * Inherits from ExtraArrayHash for easier handling of data in array form.
 */
class UserData extends ExtraArrayHash
{
	// Constants for database column names
	public const ColumnUsername = 'username';
	public const ColumnEmail = 'email';
	public const ColumnPassword = 'password';
	public const Verify = 'verify';

	/** @var string User's username */
	public string $username;

	/** @var string User's email address */
	public string $email;

	/** @var string User's password */
	public string $password;

	/** @var string Password verification (for matching during registration) */
	public string $verify;

	/** @var string Token for user verification or authentication */
	public string $token;
}

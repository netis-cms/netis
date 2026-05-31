<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign\Recovery;

use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Utils\Random;
use RuntimeException;


/** Session handler for managing password recovery tokens. */
readonly class SessionService
{
	public function __construct(
		private Session $session,
	) {
	}


	private function getSection(): SessionSection
	{
		return $this->session
			->getSection('recovery')
			->setExpiration('30 minutes');
	}


	/** Sets a new token and email for password recovery in the session. */
	public function generateToken(string $email): string
	{
		$section = $this->getSection();
		$token = Random::generate(6);
		$section->set('token', $token);
		$section->set('email', $email);
		return $token;
	}


	/**
	 * Retrieves the stored email address for password recovery from the session.
	 * @throws RuntimeException
	 */
	public function getEmail(): string
	{
		$email = $this->getSection()->get('email');
		if ($email === null) {
			throw new RuntimeException('Password recovery session has expired.');
		}
		return $email;
	}


	/** Retrieves the stored password recovery token from the session. */
	public function getToken(): ?string
	{
		return $this->getSection()
			->get('token');
	}


	/** Marks the token as checked in the session. */
	public function setTokenCheck(): void
	{
		$this->getSection()
			->set('tokenCheck', true);
	}


	/** Removes the password recovery token and token check from the session. */
	public function removeToken(): void
	{
		$this->getSection()
			->remove(['token', 'tokenCheck', 'email']);
	}


	/** Validates if the provided token matches the stored token in the session. */
	public function isTokenValid(string $token): bool
	{
		$section = $this->getSection();
		$storedToken = $section->get('token');

		if ($storedToken === null) {
			return false;
		}

		if ($section->get('tokenCheck') === true) {
			return false;
		}

		return hash_equals((string) $storedToken, $token);
	}


	/** Creates a SignRecoveryToken object based on the current session data. */
	public function createSignRecoveryToken(): Token
	{
		return new Token(
			hasToken: $this->getToken() !== null,
			isTokenChecked: (bool) $this->getSection()->get('tokenCheck'),
		);
	}
}

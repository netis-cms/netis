<?php

declare(strict_types=1);

namespace App\Core\User;

use Exception;


/**
 * Exception for situations when there is an attempt to register with a duplicate email address.
 */
class UserDuplicateEmailException extends Exception
{
}

<?php

declare(strict_types=1);

namespace App\Core\Permission;

use Drago\Application\UI\ExtraTemplate;


/** Base template. */
class BaseTemplate extends ExtraTemplate
{
	public string $offcanvasId;
	public string $modalId;
	public ?string $deleteTitle = null;
}

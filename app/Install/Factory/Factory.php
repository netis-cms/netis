<?php

declare(strict_types=1);

namespace App\Install\Factory;

use Drago\Form\ExtraForms;


/**
 * Factory for creating sign in form.
 * @extends \Drago\Application\UI\Factory<ExtraForms>
 */
readonly class Factory extends \Drago\Application\UI\Factory
{
	protected function createForm(): ExtraForms
	{
		return new ExtraForms;
	}
}

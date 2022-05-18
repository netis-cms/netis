<?php

declare(strict_types=1);

namespace App\Modules\Install\Control\Database;

use Drago\Application\UI\ExtraTemplate;
use Nette\ComponentModel\IComponent;


class DatabaseTemplate extends ExtraTemplate
{
	public IComponent $form;
}
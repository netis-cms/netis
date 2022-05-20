<?php

declare(strict_types=1);

namespace App\Modules\Backend\Access;

use App\Modules\Backend\BackendTemplate;
use Nette\ComponentModel\IComponent;


final class AccessTemplate extends BackendTemplate
{
	public IComponent $form;
}

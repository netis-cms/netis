<?php

declare(strict_types=1);

namespace App\Modules\Install\Presenters\Control\Website;

use Drago\Application\UI\ExtraTemplate;
use Nette\ComponentModel\IComponent;


class WebsiteTemplate extends ExtraTemplate
{
	public IComponent $form;
}

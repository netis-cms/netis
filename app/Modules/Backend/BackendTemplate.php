<?php

declare(strict_types=1);

namespace App\Modules\Backend;

use App\Modules\BaseTemplate;


abstract class BackendTemplate extends BaseTemplate
{
	public string $widgetPath;
}

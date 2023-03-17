<?php

declare(strict_types=1);

namespace App\Modules\Backend;

use App\Modules\Backend\Sign\User;
use App\Modules\BaseTemplate;


abstract class BackendTemplate extends BaseTemplate
{
	public string $widgetPath;
	public User $userBackend;
}

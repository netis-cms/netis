<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Drago\Utils\ExtraArrayHash;
use Nette\SmartObject;


class ArticlesForm extends ExtraArrayHash
{
	use SmartObject;
	use ArticlesMapper;
}

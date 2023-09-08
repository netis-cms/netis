<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Drago\Database\Entity;
use Nette\SmartObject;


class CommentsEntity extends Entity
{
	use SmartObject;

	public const Table = 'comments';
	public const Id = 'id';
}

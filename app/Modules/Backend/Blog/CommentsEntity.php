<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Drago\Database\Entity;


class CommentsEntity extends Entity
{
	public const Table = 'comments';
	public const PrimaryKey = 'id';
}

<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Repository;


#[Table(ArticlesEntity::table, ArticlesEntity::id)]
class ArticlesRepository
{
	use Repository;

	public function __construct(
		protected Connection $db,
	) {
	}
}

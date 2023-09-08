<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Repository;


#[Table(ArticlesEntity::Table, ArticlesEntity::Id)]
class ArticlesRepository
{
	use Repository;

	public function __construct(
		protected Connection $db,
	) {
	}
}

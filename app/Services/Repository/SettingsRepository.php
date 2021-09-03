<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Services\Entity\SettingsEntity;
use Drago\Database\Connect;
use Drago\Database\Repository;


class SettingsRepository extends Connect
{
	use Repository;

	public string $table = SettingsEntity::TABLE;
}

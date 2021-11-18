<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Services\Entity\SettingsEntity;
use Drago\Attr\Table;
use Drago\Database\Connect;


#[Table(SettingsEntity::TABLE)]
class SettingsRepository extends Connect
{
}

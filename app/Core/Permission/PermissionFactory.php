<?php

declare(strict_types=1);

namespace App\Core\Permission;

use App\Core\Permission\Authorization\AuthorizationEntity;
use App\Core\Permission\Authorization\ResourcesEntity;
use App\Core\Permission\Roles\RolesEntity;
use Dibi\Connection;
use Dibi\DriverException;
use Drago\Permission\Provider;
use Drago\Permission\Role;
use Nette\Security\Authorizator;
use Nette\Security\Permission;


/** Permission factory. */
class PermissionFactory
{
	/** @var iterable<Provider> */
	private iterable $initializers;


	/** @param iterable<Provider> $initializers */
	public function __construct(
		private readonly Connection $connection,
		iterable $initializers = [],
	) {
		$this->initializers = $initializers;
	}


	/** Creates permission object. */
	public function create(): Permission
	{
		$acl = new Permission;
		$acl->addRole(Role::RoleGuest);
		$acl->addRole(Role::RoleUser, Role::RoleGuest);
		$acl->addRole(Role::RoleAdmin, Role::RoleUser);

		foreach ($this->initializers as $initializer) {
			$initializer->register($acl);
		}

		try {
			/** @var RolesEntity[] $roles */
			$roles = $this->connection
				->select('*')
				->from(RolesEntity::Table)
				->fetchAll();

			foreach ($roles as $role) {
				if (!$acl->hasRole($role->name)) {
					$acl->addRole($role->name);
				}
			}

			/** @var ResourcesEntity[] $resources */
			$resources = $this->connection
				->select('*')
				->from(ResourcesEntity::Table)
				->fetchAll();

			foreach ($resources as $resource) {
				if (!$acl->hasResource($resource->resource)) {
					$acl->addResource($resource->resource);
				}
			}

			/** @var PermissionEntity[] $permissions */
			$permissions = $this->connection
				->select('r.name AS role, res.resource, res.privilege')
				->from(AuthorizationEntity::Table)->as('a')
				->innerJoin(RolesEntity::Table)->as('r')->on('a.role_id = r.id')
				->innerJoin(ResourcesEntity::Table)->as('res')->on('a.resource_id = res.id')
				->fetchAll();

			foreach ($permissions as $row) {
				$privilege = $row->privilege === 'all'
					? Authorizator::All
					: $row->privilege;

				$acl->allow($row->role, $row->resource, $privilege);
			}
		} catch (DriverException) {
		}

		return $acl;
	}
}

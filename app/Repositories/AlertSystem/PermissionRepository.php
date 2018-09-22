<?php

namespace App\Repositories\AlertSystem;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Permission;

class PermissionRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';
    protected $model = Permission::class;
}
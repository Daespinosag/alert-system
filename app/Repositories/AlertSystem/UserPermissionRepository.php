<?php

namespace App\Repositories\AlertSystem;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\UserPermission;

class UserPermissionRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = UserPermission::class;
}
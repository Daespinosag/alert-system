<?php

namespace App\Repositories\AlertSystem;

use App\Repositories\RepositoriesContract;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Permission;

class PermissionRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Permission::class;

    /**
     * @param string $code
     * @return mixed
     */
    public function getPermissionFromCode(string $code)
    {
        return $this->select('*')->where('code', $code)->first();

    }

    public function getPermissions(): Collection
    {
        return $this->select('*')->get();
    }
}
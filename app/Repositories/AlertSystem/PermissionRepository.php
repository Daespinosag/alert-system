<?php

namespace App\Repositories\AlertSystem;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Permission;

class PermissionRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';
    protected $model = Permission::class;


    /**
     * @param string $code
     * @return mixed
     */
    public function getPermissionFromCode(string $code)
    {
        return $this->select('*')->where('code',$code)->first();
    }
}
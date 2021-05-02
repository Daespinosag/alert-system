<?php

namespace App\Repositories\AlertSystem;

use App\Repositories\RepositoriesContract;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Role;

class RoleRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Role::class;

    /**
     * @return mixed
     */
    protected function queryBuilder()
    {
        return DB::connection('alert-system')->table('users');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRole(int $id) : Role
    {
        return $this->select('*')->where('id',$id)->first();
    }
}
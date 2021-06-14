<?php

namespace App\Repositories\AlertSystem;

use App\Repositories\RepositoriesContract;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\EloquentRepository;
use App\Entities\AlertSystem\UserPermission;
use DB;
use Carbon\Carbon;

class UserPermissionRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = UserPermission::class;

    /**
     * @return DB
     */
    protected function queryBuilder()
    {
        return DB::connection('alert-system')->table('user_permissions');
    }


    /**
     * @param int $permissionId
     * @param int $userId
     * @param bool $active
     * @param bool $activeEmail
     * @return mixed
     */
    public function assignPermissionUser(int $permissionId, int $userId, bool $active, bool $activeEmail)
    {
        return $this->queryBuilder()
            ->insert([
                'permission_id' => $permissionId,
                'user_id' => $userId,
                'active' => $active,
                'active_email' => $activeEmail,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }

    public function getPermissionUser(int $userId): Collection
    {
        return $this->select('*')->where('user_id', '=', $userId)->get();
    }
}
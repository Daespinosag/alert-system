<?php

namespace App\Repositories\AlertSystem;

use App\Repositories\RepositoriesContract;
use Illuminate\Database\Eloquent\Collection;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\UserPermission;
use DB;
use Carbon\Carbon;

class UserPermissionRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = UserPermission::class;

    /**
     * @return DB
     */
    protected function queryBuilder()
    {
        try {
            return DB::connection('alert-system')->table('user_permissions');

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserPermissionRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserPermissionRepository|queryBuilder|No pudo conectarse';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                ])
            ]);
            $log->save();
            return;
        }
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
        try {
            return $this->queryBuilder()
                ->insert([
                    'permission_id' => $permissionId,
                    'user_id' => $userId,
                    'active' => $active,
                    'active_email' => $activeEmail,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserPermissionRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserPermissionRepository|assignPermissionUser|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $permissionId,
                    $userId,
                    $active,
                    $activeEmail
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getPermissionUser(int $userId): Collection
    {
        try {

            return $this->select('*')->where('user_id', '=', $userId)->get();

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserPermissionRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserPermissionRepository|getPermissionUser|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $userId,
                ])
            ]);
            $log->save();
            return new Collection();
        }
    }
}
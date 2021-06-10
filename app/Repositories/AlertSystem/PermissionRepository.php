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
        try {
            return $this->select('*')->where('code', $code)->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'PermissionRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|PermissionRepository|getPermissionFromCode|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $code
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getPermissions(): Collection
    {
        try {
            return $this->select('*')->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'PermissionRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|PermissionRepository|getPermissions|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                ])
            ]);
            $log->save();
            return new Collection();
        }
    }
}
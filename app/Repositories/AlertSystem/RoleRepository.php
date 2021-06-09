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
        try {
            return DB::connection('alert-system')->table('users');
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'RoleRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|RoleRepository|queryBuilder|No pudo conectarse';
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
     * @param $id
     * @return mixed
     */
    public function getRole(int $id): Role
    {
        try {
            return $this->select('*')->where('id', $id)->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'RoleRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|RoleRepository|getRole|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $id
                ])
            ]);
            $log->save();
            return null;
        }
    }
}
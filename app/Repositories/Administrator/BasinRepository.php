<?php

namespace App\Repositories\Administrator;

use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use DB;
use Illuminate\Support\Collection;
use App\Repositories\EloquentRepository;
use App\Entities\Administrator\Basin;

class BasinRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Basin::class;

    /**
     * @return mixed
     */
    protected function queryBuilder()
    {
        try {
            return DB::connection('administrator')->table('basin');
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'BasinRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|BasinRepository|queryBuilder|No pudo conectar';
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
     * @param array $basins
     * @return Collection
     */
    public function getBasinsById(array $basins): Collection
    {
        try {
            return $this->queryBuilder()->select('id', 'name', 'code', 'description', 'kml')->whereIn('id', $basins)->get();

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'BasinRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|BasinRepository|getBasinsById|No pudo obtener los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $basins
                ])
            ]);
            $log->save();
            return;
        }
    }
}
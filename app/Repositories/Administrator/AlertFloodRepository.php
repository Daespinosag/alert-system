<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\AlertFlood;
use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use Illuminate\Support\Facades\DB;
use Rinvex\Repository\Repositories\EloquentRepository;

class AlertFloodRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = AlertFlood::class;

    /**
     * @return mixed
     */
    protected function queryBuilder()
    {
        try {
            return DB::connection('administrator')->table('alert_flood');
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AlertFloodRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|AlertFloodRepository|queryBuilder|No se pudo conectar';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([

                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();
            return;
        }
    }

    public function getAlerts()
    {
        try {
            return $this->select('id', 'basin_id', 'name', 'code', 'active', 'limit_red as limitRed', 'icon')->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AlertFloodRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|AlertFloodRepository|getAlerts|No pudo recuperars las alertas';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([

                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();
            return;
        }
    }
}
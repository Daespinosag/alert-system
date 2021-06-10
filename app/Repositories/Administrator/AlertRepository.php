<?php

namespace App\Repositories\Administrator;

use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use DB;
use App\Repositories\EloquentRepository;
use App\Entities\Administrator\Alert;

class AlertRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Alert::class;

    /**
     * @return mixed
     */
    protected function queryBuilder()
    {

        try {
            return DB::connection('administrator')->table('alert');
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AlertRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|AlertRepository|queryBuilder|No pudo conectar';
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
     * @param $alertCode
     * @return mixed
     */

    public function getLevelAlert($alertCode)
    {
        try {
            return $this->queryBuilder()
                ->select('alert.id', 'alert.code', 'level_alert.*')
                ->join('level_alert', 'level_alert.alert_id', '=', 'alert.id')
                ->where('alert.code', '=', $alertCode)
                ->orderBy('level_alert.level', 'DESC')
                ->get();

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AlertRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|AlertRepository|getLevelAlert|No se recuperaron las alertas';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $alertCode
                ])
            ]);
            $log->save();
            return;
        }
    }

    /**
     * @return mixed
     */
    public function getAlerts()
    {
        try {
            return $this->select('*')->where('active', '=', true)->get()->toArray();

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AlertRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|AlertRepository|getAlerts|No pudo recuperar las alertas';
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
     * @param array $codes
     * @return mixed
     */
    public function getAlertsWhereIn(array $codes)
    {
        try {
            return $this->select('*')->where('active', '=', true)->whereIn('code', $codes)->get()->toArray();

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AlertRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|AlertRepository|getAlertsWhereIn|No pudo recuperar las alertas';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $codes
                ])
            ]);
            $log->save();
            return;
        }
    }

}
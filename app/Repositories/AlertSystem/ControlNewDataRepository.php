<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\ControlNewData;
use App\Repositories\RepositoriesContract;
use Illuminate\Support\Collection;
use Rinvex\Repository\Repositories\EloquentRepository;

class ControlNewDataRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = ControlNewData::class;

    /**
     * @param string $alertCode
     * @return Collection
     */
    public function getUnsettledAlerts(string $alertCode): Collection
    {
        try {
            return $this->select('*')->where('alert_code', '=', $alertCode)->where('active', '=', true)->where('homogenization', '=', false)->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'ControlNewDataRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|ControlNewDataRepository|getUnsettledAlerts|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $alertCode
                ])
            ]);
            $log->save();
            return [];
        }
    }

    /**
     * @param string $alertCode
     * @return Collection
     */
    public function getUnsettledAlertsSpecific(string $alertCode, $alertsId): Collection
    {
        try {
            return $this->select('*')->where('alert_code', '=', $alertCode)->where('active', '=', true)->where('homogenization', '=', false)->whereIn('id', $alertsId)->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'ControlNewDataRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|ControlNewDataRepository|getUnsettledAlertsSpecific|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $alertCode,
                    $alertsId
                ])
            ]);
            $log->save();
            return [];
        }
    }
}
<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\TrackingFloodAlert;
use App\Repositories\RepositoriesContract;
use PhpParser\Node\Expr\Cast\Object_;
use Rinvex\Repository\Repositories\EloquentRepository;

class TrackingFloodAlertRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = TrackingFloodAlert::class;

    public function newObject()
    {
        return new TrackingFloodAlert();
    }

    /**
     * @param string $dateTime
     * @param int $supId
     * @param int $alertId
     * @param int $stationSk
     * @return mixed
     */
    public function getFromDate(string $dateTime, int $supId, int $alertId, int $stationSk)
    {
        try {
            return $this->select('*')
                ->where('sup_id', '=', $supId)
                ->where('alert_id', '=', $alertId)
                ->where('primary_station_id', '=', $stationSk)
                ->where('date_time_homogenization', '=', $dateTime)
                ->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'TrackingFloodAlertRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|TrackingFloodAlertRepository|getFromDate|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $dateTime,
                    $supId,
                    $alertId,
                    $stationSk
                ])
            ]);
            $log->save();
            return;
        }
    }

    /**
     * @param string $initialDateTime
     * @param string $finalDateTime
     * @param int $supId
     * @param int $alertId
     * @param int $stationSk
     * @param string $localVariable espera  rainfall
     * @return object
     */
    public function calculateIndicator(string $initialDateTime, string $finalDateTime, int $supId, int $alertId, int $stationSk, string $localVariable)
    {
        try {
            return (Object)$this->selectRaw('SUM(' . $localVariable . ') AS indicator, COUNT(' . $localVariable . ') AS recovered')
                ->where('sup_id', '=', $supId)
                ->where('alert_id', '=', $alertId)
                ->where('primary_station_id', '=', $stationSk)
                ->whereBetween('date_time_homogenization', [$initialDateTime, $finalDateTime])
                ->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'TrackingFloodAlertRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|TrackingFloodAlertRepository|calculateIndicator|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $initialDateTime,
                    $finalDateTime,
                    $supId,
                    $alertId,
                    $stationSk,
                    $localVariable
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getLastInformation(int $typeAlertId, int $alertId, int $stationId)
    {
        try {
            return $this->select('*')
                ->where('sup_id', '=', $typeAlertId)
                ->where('alert_id', '=', $alertId)
                ->where('primary_station_id', '=', $stationId)
                ->get()
                ->last();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'TrackingFloodAlertRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|TrackingFloodAlertRepository|getLastInformation|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $typeAlertId,
                    $alertId,
                    $stationId
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getAllTrackinByStationId($stationId, $alertId, $date)
    {
        try {
            return $this->select('alert_id', 'primary_station_id', 'rainfall', 'indicator_value')
                ->where('primary_station_id', "=", $stationId)
                ->where('alert_id', "=", $alertId)
                ->whereBetween('date_time_initial', [$date->startDate, $date->endDate])
                ->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'TrackingFloodAlertRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|TrackingFloodAlertRepository|getAllTrackinByStationId|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $stationId,
                    $alertId,
                    $date
                ])
            ]);
            $log->save();
            return;
        }
    }
}
<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\TrackingFloodAlert;
use App\Repositories\RepositoriesContract;
use PhpParser\Node\Expr\Cast\Object_;
use App\Repositories\EloquentRepository;

class TrackingFloodAlertRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
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
        return $this->select('*')
            ->where('sup_id', '=', $supId)
            ->where('alert_id', '=', $alertId)
            ->where('primary_station_id', '=', $stationSk)
            ->where('date_time_homogenization', '=', $dateTime)
            ->first();
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
        return (Object)$this->selectRaw('SUM(' . $localVariable . ') AS indicator, COUNT(' . $localVariable . ') AS recovered')
            ->where('sup_id', '=', $supId)
            ->where('alert_id', '=', $alertId)
            ->where('primary_station_id', '=', $stationSk)
            ->whereBetween('date_time_homogenization', [$initialDateTime, $finalDateTime])
            ->first();
    }

    public function getLastInformation(int $typeAlertId, int $alertId, int $stationId)
    {
        return $this->select('*')
            ->where('sup_id', '=', $typeAlertId)
            ->where('alert_id', '=', $alertId)
            ->where('primary_station_id', '=', $stationId)
            ->get()
            ->last();
    }

    public function getAllTrackinByStationId($stationId, $alertId, $date)
    {

        return $this->select('alert_id', 'primary_station_id', 'rainfall', 'indicator_value','date_time_homogenization')
            ->where('primary_station_id', "=", $stationId)
            ->where('alert_id', "=", $alertId)
            ->whereBetween('date_time_initial', [$date->startDate, $date->endDate])
            ->get();
    }
}
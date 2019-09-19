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

    public function newObject(){
        return new TrackingFloodAlert();
    }
    /**
     * @param string $dateTime
     * @param int $supId
     * @param int $alertId
     * @param int $stationSk
     * @return mixed
     */
    public function getFromDate(string $dateTime,int $supId,int $alertId,int $stationSk){
        return $this->select('*')
            ->where('sup_id','=',$supId)
            ->where('alert_id','=',$alertId)
            ->where('primary_station_id','=',$stationSk)
            ->where('date_time_homogenization','=',$dateTime)
            ->first();
    }

    /**
     * @param string $initialDateTime
     * @param string $finalDateTime
     * @param int $supId
     * @param int $alertId
     * @param int $stationSk
     * @return object
     */
    public function calculateIndicator(string $initialDateTime,string $finalDateTime,int $supId,int $alertId,int $stationSk){
        return (Object)$this->selectRaw('SUM(rainfall) AS indicator, COUNT(rainfall) AS recovered')
                            ->where('sup_id','=',$supId)
                            ->where('alert_id','=',$alertId)
                            ->where('primary_station_id','=',$stationSk)
                            ->whereBetween('date_time_homogenization',[$initialDateTime,$finalDateTime])
                            ->get()->toArray()[0];
    }
}
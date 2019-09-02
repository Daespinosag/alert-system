<?php


namespace App\AlertSystem\AlertsV2;

use App\AlertSystem\AlertSystem;
use App\Entities\Administrator\Station;
use App\Repositories\Administrator\StationRepository;
use Carbon\Carbon;

class AlertBase extends AlertSystem
{
    /**
     * @var Station
     */
    protected $primaryStation;

    /**
     * @var StationAlert
     */
    protected $primaryStationAlert;

    /**
     * @var StationAlert[]
     */
    protected $secondaryStationsAlert = [];

    /**
     * @var Carbon
     */
    protected $initDateTime;

    /**
     * @var Carbon
     */
    protected $finalDateTime;

    /**
     * @var StationRepository
     */
    protected $stationRepository;
    /**
     * @var Carbon
     */
    private $dateTime;

    /**
     * FloodAlert constructor.
     * @param int $primaryStation
     * @param Carbon $dateTime
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime
     */
    public function __construct(int $primaryStation, Carbon $dateTime,Carbon $initDateTime,Carbon $finalDateTime){
        $this->primaryStation = $primaryStation;
        $this->dateTime = $dateTime;
        $this->initDateTime = $initDateTime;
        $this->finalDateTime = $finalDateTime;

        $this->stationRepository = new StationRepository(); # TODO Esto debe ser dinamico

        $this->primaryStationAlert = $this->createStation($primaryStation);
    }

    /**
     * @param int $stationId
     * @return StationAlert
     */
    public function createStation(int $stationId) : StationAlert{
        return new StationAlert($this->stationRepository->getStation($stationId), $this->dateTime,$this->initDateTime, $this->finalDateTime);
    }
}
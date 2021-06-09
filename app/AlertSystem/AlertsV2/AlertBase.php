<?php

namespace App\AlertSystem\AlertsV2;

use App\AlertSystem\Indicators\IndicatorContract;
use App\Entities\Administrator\Station;
use App\Entities\AlertSystem\ControlNewData;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\RepositoriesContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AlertBase
{
    /**
     * @var string
     */
    protected $alertCode;
    /**
     * @var
     */
    public $alert;
    /**
     * @var ControlNewData
     */
    public $controlNewData;
    /**
     * @var Station
     */
    protected $primaryStation;

    /**
     * @var BackupStationsAlert
     */
    protected $backupStationsAlert;

    /**
     * @var StationAlert
     */
    public $primaryStationAlert;

    /**
     * @var
     */
    protected $variableToValidate;

    /**
     * @var Carbon
     */
    public $initDateTime;

    /**
     * @var Carbon
     */
    public $finalDateTime;

    /**
     * @var StationRepository
     */
    protected $stationRepository;

    /**
     * @var
     */
    protected $specificAlertRepository;
    /**
     * @var Carbon
     */
    public $dateTime;

    /**
     * @var bool
     */
    public $complete = false;

    /**
     * @var IndicatorContract
     */
    public $indicator;
    /**
     * @var RepositoriesContract
     */
    private $alertRepository;

    public $config;
    /**
     * FloodAlert constructor.
     * @param RepositoriesContract $specificAlertRepository
     * @param RepositoriesContract $alertRepository
     * @param string $alertCode
     * @param string $variableToValidate
     * @param ControlNewData $controlNewData
     * @param Carbon $dateTime
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime
     */
    public function __construct(
        RepositoriesContract $specificAlertRepository,
        RepositoriesContract $alertRepository,
        string $alertCode,
        string $variableToValidate,
        ControlNewData $controlNewData,
        Carbon $dateTime,
        Carbon $initDateTime,
        Carbon $finalDateTime,
        $config = null
    )
    {
        $this->specificAlertRepository = $specificAlertRepository;
        $this->alertRepository = $alertRepository;
        $this->alertCode = $alertCode;
        $this->variableToValidate = $variableToValidate;
        $this->controlNewData = $controlNewData;
        $this->dateTime = $dateTime;
        $this->initDateTime = $initDateTime;
        $this->finalDateTime = $finalDateTime;
        $this->config = $config;
        $this->alert = $this->alertRepository->getAlert($this->controlNewData->alert_id);
        $this->stationRepository = new StationRepository(); # TODO Esto debe ser dinamico
        $this->primaryStation = $this->getStationAlert(true)[0]; # TODO Validar que hacer cuando no se encuentra estacion primaria
        $this->primaryStationAlert = $this->createStation($this->primaryStation);

    }

    /**
     * @param $station
     * @return StationAlert
     */
    public
    function createStation($station): StationAlert
    {
        return new StationAlert($station, $this->dateTime, $this->initDateTime, $this->finalDateTime);
    }

    /**
     * @param bool $primary
     * @return Collection
     */
    public
    function getStationAlert(bool $primary = false): Collection
    {
        return $this->stationRepository->getStationsAlerts($this->alertCode, $this->controlNewData->alert_id, $primary);
    }

    /**
     *
     */
    public
    function createBackupStationsAlert()
    {
        $this->backupStationsAlert = new BackupStationsAlert(
            $this->stationRepository->getStationsAlerts($this->alertCode, $this->controlNewData->alert_id, false),
            $this->dateTime,
            $this->initDateTime,
            $this->finalDateTime
        );
    }

}
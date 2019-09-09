<?php

namespace App\AlertSystem\AlertsV2;

use App\AlertSystem\Extract\{AcquisitionServerExtract, ExtractContract};
use App\AlertSystem\Homogenization\Homogenization;
use App\Entities\Administrator\Station;
use Carbon\Carbon;

class StationAlert
{
    /**
     * @var Station
     */
    private $station;

    /**
     * @var Carbon
     */
    private $initDateTime;

    /**
     * @var Carbon
     */
    private $finalDateTime;

    /**
     * @var
     */
    public $exactMethod;
    /**
     * @var Carbon
     */
    private $dateTime;

    /**
     * @var Homogenization
     */
    public $homogenization;

    /**
     * StationAlert constructor.
     * @param $station
     * @param Carbon $dateTime
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime
     */
    public function __construct($station,Carbon $dateTime,Carbon $initDateTime, Carbon $finalDateTime){

        $this->station = $station;
        $this->dateTime = $dateTime;
        $this->initDateTime = $initDateTime;
        $this->finalDateTime = $finalDateTime;

        $this->exactMethod = $this->createAcquisitionServerExtract($station->connection_name,$station->station_table);
        $this->homogenization = new Homogenization($dateTime);
    }

    /**
     * @param string $connection
     * @param string $tableName
     * @return AcquisitionServerExtract
     */
    public function createAcquisitionServerExtract(string $connection = '',string $tableName) : ExtractContract {
         return new AcquisitionServerExtract($connection,$tableName,$this->initDateTime, $this->finalDateTime);
    }

    public function execute(string $variable){
        $this->exactMethod->execute();

        if ($this->exactMethod->dataExistence){
            $this->homogenization->execute($this->exactMethod->data,$variable);
        }
    }
}
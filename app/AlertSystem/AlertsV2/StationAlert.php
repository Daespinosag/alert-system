<?php

namespace App\AlertSystem\AlertsV2;

use App\AlertSystem\Extract\AcquisitionServerExtract;
use App\AlertSystem\Extract\ExtractContract;
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
     * @param Station $station
     * @param Carbon $dateTime
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime
     */
    public function __construct(Station $station,Carbon $dateTime,Carbon $initDateTime, Carbon $finalDateTime){
        $this->station = $station;
        $this->dateTime = $dateTime;
        $this->initDateTime = $initDateTime;
        $this->finalDateTime = $finalDateTime;

        $this->exactMethod = $this->createAcquisitionServerExtract($station->net->connection->name,$station->table_db_name);
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

    public function execute(){
        $this->exactMethod->execute();

        if ($this->exactMethod->dataExistence){
            $this->homogenization->execute($this->exactMethod->data,'precipitacion_real'); # La variable dede entrar por parametro
        }
    }
}
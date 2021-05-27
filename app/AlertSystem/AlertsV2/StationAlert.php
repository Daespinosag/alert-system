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
        # Se ejecuta el metodo de extraccion de datos
        $this->exactMethod->execute($variable);

        # Se valida si existen datos
        if (!$this->exactMethod->dataExistence){ return;} # TODO definir eventos de fallo aca.

        # Se ejecuta la homogenizacion
        $this->homogenization->execute($this->exactMethod->data,$variable);

        # Se valida la homogenizacion
        if (!$this->homogenization->validateHomogenization){return;} # TODO definir eventos de fallo aca.

        # Se calcula el peso de la estacion
        $weight = (is_null($this->station->station_alert_distance)) ? 1 : $this->station->station_alert_distance;

        $this->homogenization->data->{'weight_'.$variable} = $this->homogenization->data->{$variable} * $weight;

        return; # TODO definir eventos para defirnir que todo salio correcto
    }
}
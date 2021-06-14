<?php

namespace App\AlertSystem\AlertsV2;

use App\AlertSystem\Extract\{AcquisitionServerExtract, ExtractContract};
use App\AlertSystem\Homogenization\Homogenization;
use App\Entities\Administrator\Station;
use App\Repositories\AlertSystem\LogsRepository;
use Carbon\Carbon;

class StationAlert
{
    /**
     * @var Station
     */
    public $station;

    /**
     * @var Carbon
     */
    public $initDateTime;

    /**
     * @var Carbon
     */
    public $finalDateTime;

    /**
     * @var
     */
    public $exactMethod;
    /**
     * @var Carbon
     */
    public $dateTime;

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
    public function __construct($station, Carbon $dateTime, Carbon $initDateTime, Carbon $finalDateTime)
    {
        $this->station = $station;
        $this->dateTime = $dateTime;
        $this->initDateTime = $initDateTime;
        $this->finalDateTime = $finalDateTime;

        $this->exactMethod = $this->createAcquisitionServerExtract($station->connection_name, $station->station_table);
        $this->homogenization = new Homogenization($dateTime);
    }

    /**
     * @param string $connection
     * @param string $tableName
     * @return AcquisitionServerExtract
     */
    public function createAcquisitionServerExtract(string $connection = '', string $tableName): ExtractContract
    {
        return new AcquisitionServerExtract($connection, $tableName, $this->initDateTime, $this->finalDateTime);
    }

    public function execute(string $variable)
    {
        # Se ejecuta el metodo de extraccion de datos
        $this->exactMethod->execute($variable);

        # Se valida si existen datos
        if (!$this->exactMethod->dataExistence) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationAlert';
            $log->type = 'Fallo';
            $log->status = 'Active';
            $log->priority = 'Med';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|AlertsV2|StationAlert|execute|No Existen datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => '',
                'parametersIn' => json_encode([
                    $variable
                ])
            ]);
            $log->save();
            return;
        }

        # Se ejecuta la homogenizacion
        $this->homogenization->execute($this->exactMethod->data, $variable);

        # Se valida la homogenizacion
        if (!$this->homogenization->validateHomogenization) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationAlert';
            $log->type = 'Fallo';
            $log->status = 'Active';
            $log->priority = 'Med';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|AlertsV2|StationAlert|execute|No se valida la homogenización';
            $log->aditionalData = json_encode([
                'exeptionMessage' => '',
                'parametersIn' => json_encode([
                    $variable
                ])
            ]);
            $log->save();
            return;
        }

        # Se calcula el peso de la estacion
        $weight = (is_null($this->station->station_alert_distance)) ? 1 : $this->station->station_alert_distance;

        $this->homogenization->data->{'weight_' . $variable} = $this->homogenization->data->{$variable} * $weight;

        return;
    }
}
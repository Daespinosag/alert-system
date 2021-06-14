<?php

namespace App\AlertSystem\AlertsV2;

use App\AlertSystem\Indicators\A25Indicator;
use App\Entities\AlertSystem\ControlNewData;
use App\Entities\AlertSystem\TrackingLandslideAlert;
use App\Events\AlertLandslideEvent;
use App\Repositories\Administrator\AlertLandslideRepository;
use App\Repositories\AlertSystem\LandslideRepository;
use Carbon\Carbon;

class LandslideAlert extends AlertBase implements AlertContract
{

    /**
     * FloodAlert constructor.
     * @param string $alertCode
     * @param ControlNewData $controlNewData
     * @param Carbon $dateTime
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime LandslideRepository
     */
    public function __construct(string $alertCode, ControlNewData $controlNewData, Carbon $dateTime, Carbon $initDateTime, Carbon $finalDateTime, $config = null)
    {
        parent::__construct(new AlertLandslideRepository(), new LandslideRepository(), $alertCode, 'precipitacion_real', $controlNewData, $dateTime, $initDateTime, $finalDateTime, $config);
    }

    /**
     *
     */
    public function execute()
    {
        # Se ejecuta la consulta de la variable para la estacion primaria
        $this->primaryStationAlert->execute($this->variableToValidate);

        # Se valida si fue posible realizar el calculo con la estacion primaria
        if ($this->primaryStationAlert->homogenization->validateHomogenization) {
            # Se crea el objeto para el calculo del indicador
            $this->setIndicator($this->primaryStationAlert->homogenization->data, $this->config);

            # Se calcula el  indicador dependi
            $this->calculateIndicator(true);

            # Se completa da por completada la alerta
            $this->complete = true;

            return;
        }

        # Se crea el componente mediador para las estaciones de respado
        $this->createBackupStationsAlert();

        # Se ejecuta la el componente mediador para las estaciones secundarias
        $this->backupStationsAlert->execute($this->variableToValidate);

        # Se valida la ejecusion del proceso por medio de las estaciones de respaldo
        if ($this->backupStationsAlert->complete) {

            # Se crea el objeto para el calculo del indicador
            $this->setIndicator($this->backupStationsAlert->data);

            # Se calcula el  indicador dependi
            $this->calculateIndicator(false);
            $this->complete = true;

            return;
        }

        #se guarda el registro de igual forma si se tuvieron errores
        $this->actualTracking = new TrackingLandslideAlert();
        $this->actualTracking->sup_id = 2;
        $this->actualTracking->alert_id = $this->primaryStationAlert->station->net_id;
        $this->actualTracking->primary_station_id = $this->primaryStationAlert->station->station_sk;
        $this->actualTracking->rainfall_recovered = 0;
        $this->actualTracking->alert_tag = 'green';
        $this->actualTracking->alert_status = 'equal';
        $this->actualTracking->date_time_homogenization = $this->primaryStationAlert->dateTime;
        $this->actualTracking->date_time_initial = $this->primaryStationAlert->initDateTime;
        $this->actualTracking->date_time_final = $this->primaryStationAlert->finalDateTime;

        if (is_bool($this->primaryStationAlert->exactMethod->connection)) {
            //error no conection
            $this->actualTracking->error = 'communication';
            $this->complete = true;
            $this->actualTracking->save();
            return;
        }
        if (!$this->primaryStationAlert->exactMethod->dataExistence) {
            //error no existencia de datos
            $this->actualTracking->error = 'no_data';
            $this->complete = true;
            $this->actualTracking->save();
            return;
        }
        //error de homogenizacion
        $this->actualTracking->error = 'no_homogenization';
        $this->complete = true;
        $this->actualTracking->save();
        return;
    }

    /**
     * @param $value
     */
    public function setIndicator($value)
    {
        $this->indicator = new A25Indicator($value, $this->config);
    }

    /**
     * @param bool $primaryProcess
     */
    public function calculateIndicator(bool $primaryProcess = true)
    {
        $this->indicator->execute(
            $this->controlNewData->alert_id,
            $this->primaryStation->station_sk,
            'weight_' . $this->variableToValidate, $primaryProcess,
            [
                'red' => (float)$this->alert->limit_red,
                'yellow' => (float)$this->alert->limit_yellow,
                'orange' => (float)$this->alert->limit_orange
            ]
        );
    }

}
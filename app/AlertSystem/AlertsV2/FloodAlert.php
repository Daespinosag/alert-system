<?php

namespace App\AlertSystem\AlertsV2;

use App\AlertSystem\Indicators\A10MinIndicator;
use App\Entities\AlertSystem\ControlNewData;
use App\Entities\AlertSystem\TrackingFloodAlert;
use App\Helpers\Log;
use App\Repositories\Administrator\AlertFloodRepository;
use App\Repositories\AlertSystem\FloodRepository;
use Carbon\Carbon;

class FloodAlert extends AlertBase implements AlertContract
{
    /**
     * FloodAlert constructor.
     * @param string $alertCode
     * @param ControlNewData $controlNewData
     * @param Carbon $dateTime
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime
     */
    public function __construct(string $alertCode, ControlNewData $controlNewData, Carbon $dateTime, Carbon $initDateTime, Carbon $finalDateTime, $config = null)
    {
        parent::__construct(new AlertFloodRepository(), new FloodRepository(), $alertCode, 'precipitacion_real', $controlNewData, $dateTime, $initDateTime, $finalDateTime, $config);
    }

    /**
     *
     */
    public function execute()
    {
        try {
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
            $this->actualTracking = new TrackingFloodAlert();
            $this->actualTracking->sup_id = 1;
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
        } catch (Exception $e) {
            Log::newError('FloodAlert', 'Max', 'AlertSystem|AlertsV2|FloodAlert|execute|No pudo recuperar los datos', $e, [$this]);
        }
    }

    /**
     * @param $value
     */
    public function setIndicator($value, $config = null)
    {

        $this->indicator = new A10MinIndicator($value, $config);
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
            ['red' => (float)$this->alert->limit_red]
        );
    }


}
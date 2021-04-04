<?php

namespace App\AlertSystem\AlertsV2;

use App\AlertSystem\Indicators\A10MinIndicator;
use App\Entities\AlertSystem\ControlNewData;
use App\Events\AlertFloodEvent;
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
    public function __construct(string $alertCode,ControlNewData $controlNewData, Carbon $dateTime,Carbon $initDateTime,Carbon $finalDateTime){
        parent::__construct(new AlertFloodRepository(),new FloodRepository(),$alertCode,'precipitacion_real',$controlNewData,$dateTime,$initDateTime,$finalDateTime);
    }

    /**
     *
     */
    public function execute(){
        # Se ejecuta la consulta de la variable para la estacion primaria
        $this->primaryStationAlert->execute($this->variableToValidate);

        # Se valida si fue posible realizar el calculo con la estacion primaria
        if ($this->primaryStationAlert->homogenization->validateHomogenization){
            # Se crea el objeto para el calculo del indicador
            $this->setIndicator($this->primaryStationAlert->homogenization->data);

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
        if ($this->backupStationsAlert->complete){

            # Se crea el objeto para el calculo del indicador
            $this->setIndicator($this->backupStationsAlert->data);

            # Se calcula el  indicador dependi
            $this->calculateIndicator(false);

            $this->complete = true;

            return;
        }

        //dd('No fue posible calcular el indicador de inundacion');
    }

    /**
     * @param $value
     */
    public function setIndicator($value){
        $this->indicator = new A10MinIndicator($value);
    }
    /**
     * @param bool $primaryProcess
     */
    public function calculateIndicator(bool $primaryProcess = true){

        $this->indicator->execute(
            $this->controlNewData->alert_id,
            $this->primaryStation->station_sk,
            'weight_'.$this->variableToValidate,$primaryProcess,
            ['red'=> (float)$this->alert->limit_red]
        );
    }

    public function formatDataToEvent() : array
    {
        $arr = [];

        foreach ($this->values as $value) {
            $temporalArr = [];
            $temporalArr['alert'] = $this->code;
            $temporalArr['station'] = $value['station'];
            $temporalArr['change_alert'] = $value['change_alert'];
            $temporalArr['values'][$this->code . '_value'] = $value[$this->code . '_value'];
            $temporalArr['values']['alert'] = $value['alert'];
            $temporalArr['values']['date_execution'] = $value['date_execution'];
            $temporalArr['values']['error'] = $value['error'];
            $temporalArr['values']['comment'] = $value['comment'];

            array_push($arr, $temporalArr);
        }
    }
        public function sendDataToEvent(){
        $data = $this->formatDataToEvent();
        event(new AlertFloodEvent($data));
    }
}
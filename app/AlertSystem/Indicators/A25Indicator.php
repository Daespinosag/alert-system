<?php

namespace App\AlertSystem\Indicators;

use App\Repositories\AlertSystem\TrackingLandslideAlertRepository;

class A25Indicator extends IndicatorsBase implements IndicatorContract
{
    /**
     * A10MinIndicator constructor.
     * @param $value
     */
    public function __construct($value){
        parent::__construct(new TrackingLandslideAlertRepository(),5,2,$value,'rainfall');
        # TODO 5 y rainfall deben ingresar por medio de un archivo de configuracion
    }

    /**
     * @param int $alertId
     * @param int $stationSk
     * @param string $variable
     * @param bool $primary
     * @param array $validation
     */
    public function execute(int $alertId,int $stationSk,string $variable,bool $primary,array $validation){

        $supId = 1; # TODO Esto debe ser dinamico Basin o Zone

        # Se gerenran las fechas espeficias para trabajar
        $this->generateRageDateTime();

        #Se inicializa el objeto Tracking Con los parametros especificos
        $this->initializationTracking($supId,$alertId,$stationSk,$variable,$primary);

        # Se extrae el valor anterior del tacking
        $this->getBeforeIndicatorTracking();

        # Se calcula el valor del indicador hasta el momento y la cantidad de valores recuperados
        $this->calculatePartialIndicator();

        # Se calcula el indicador general
        $this->calculateIndicator();

        # Se valida el nivel de alerta para el indicador calculado
        $this->validateAlertLevels($validation);

        # Se valida la diferencia con el valor de tacking anterior
        $this->validatePreviousDeference();

        # Se guarda el valor calculado para el indicador
        $this->insertInTrackingTable =  $this->actualTracking->save();
    }

    /**
     * @param array $validation
     */
    public function validateAlertLevels(array $validation){
        $exit = false;
        foreach ($validation as $key => $value){
            if (!$exit){ $exit = $this->validateIndicator($key,$value);}
        }
    }
}
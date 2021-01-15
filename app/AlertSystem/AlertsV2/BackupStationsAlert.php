<?php

namespace App\AlertSystem\AlertsV2;

use Illuminate\Support\Collection;
use Carbon\Carbon;

class BackupStationsAlert
{
    /**
     * @var Collection
     */
    protected $stations;

    /**
     * @var StationAlert[]
     */
    protected $stationsAlert = [];
    /**
     * @var Carbon
     */
    private $dateTime;
    /**
     * @var Carbon
     */
    private $initDateTime;
    /**
     * @var Carbon
     */
    private $finalDateTime;
    /**
     * @var bool
     */
    public $complete = false;
    /**
     * @var array
     */
    public $data = [];

    /**
     * BackupStationsAlert constructor.
     * @param Collection $stations
     * @param Carbon $dateTime
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime
     */
    public function __construct(Collection $stations,Carbon $dateTime,Carbon $initDateTime, Carbon $finalDateTime){
        $this->stations = $stations;
        $this->dateTime = $dateTime;
        $this->initDateTime = $initDateTime;
        $this->finalDateTime = $finalDateTime;

        $this->createStationsAlert();
    }

    /**
     * @param string $variable
     */
    public function execute(string $variable){

        # Se extrae toda la informacion para cada una de las estaciones secundarias
        $this->executeStationAlerts($variable);

        # Se valida si alguna de las estacion no ha transmitido
        if (!$this->validateHomogenization()){return;}  # TODO No fue posible realizar el calculo

        # Se calcula el valor compuesto para la variable especifica
        $this->data = $this->calculateVariableWithStations($variable);

        # Se etiqueta el proceso como terminado
        $this->complete = true;
    }

    /**
     *
     */
    protected function createStationsAlert(){
        foreach ($this->stations as $station){
            $this->stationsAlert[] = new StationAlert($station,$this->dateTime,$this->initDateTime,$this->finalDateTime);
        }
    }

    /**
     * @param string $variable
     */
    protected function executeStationAlerts(string $variable){
        foreach ($this->stationsAlert as $stationAlert){
            $stationAlert->execute($variable);
        }
    }

    /**
     * @return bool
     */
    public function validateHomogenization() : bool {
        $flag = true;
        foreach ($this->stationsAlert as $stationAlert){
            if ($flag){$flag = $stationAlert->homogenization->validateHomogenization;}
        }
        return $flag;
    }

    /**
     * @param string $variable
     * @return array
     */
    public function calculateVariableWithStations(string $variable){
        $value = 0;
        foreach ($this->stationsAlert as $stationAlert){
            $value += $stationAlert->homogenization->data->{'weight_'.$variable};
        }

        $element = $this->stationsAlert[0]->homogenization->data;
        $element->{'weight_'.$variable} = $value;

        return $element;
    }
}
<?php

namespace App\AlertSystem\Indicators;

use App\Repositories\RepositoriesContract;
use Carbon\Carbon;

class IndicatorsBase
{
    /**
     * @var
     */
    public $actualTracking;
    /**
     * @var
     */
    protected $value;
    /**
     * @var RepositoriesContract
     */
    private $trackingTableRepository;
    /**
     * @var int
     */
    private $range;
    /**
     * @var int
     */
    private $period = 5;
    /**
     * @var string
     */
    private $beforeDateTime;
    /**
     * @var
     */
    private $beforeIndicatorTracking;
    /**
     * @var
     */
    private $infoIndicator;
    /**
     * @var string
     */
    private $localVariable;
    /**
     * @var array
     */
    private $alertLevels = ['green','yellow','orange','red'];
    /**
     * @var bool
     */
    public $insertInTrackingTable = false;
    /**
     * @var integer
     */
    private $expectedAmount;

    /**
     * IndicatorsBase constructor.
     * @param RepositoriesContract $trackingTableRepository
     * @param int $range
     * @param int $expectedAmount
     * @param $value : value in minutes
     * @param string $localVariable
     */
    public function __construct(RepositoriesContract $trackingTableRepository,int $range,int $expectedAmount,$value,string $localVariable){
        $this->trackingTableRepository = $trackingTableRepository;
        $this->value = $value;
        $this->range = $range;
        $this->expectedAmount = $expectedAmount;
        $this->localVariable = $localVariable;

        # Se crea el objeto Tracking especifico con el que se va a seguir interactuando
        $this->actualTracking = $this->trackingTableRepository->newObject();
    }

    protected function generateRageDateTime(){
        # Se calcula la fecha homogenizada para el calculo del indicador
        $this->actualTracking->date_time_homogenization = $this->value->dateTime;

        # Se calcula la fecha final para el calculo del indicador
        $this->actualTracking->date_time_final = $this->value->dateTime;

        # Se calcula la fecha inicial para el calculo del indicador
        $this->actualTracking->date_time_initial = (Carbon::parse($this->value->dateTime))->addSeconds( - $this->range * 60 )->format('Y-m-d H:i:s');

        # Se calcula la fecha inicial para el calculo del indicador
        $this->beforeDateTime = (Carbon::parse($this->value->dateTime))->addSeconds( - $this->period * 60 )->format('Y-m-d H:i:s');
    }

    /**
     * @param int $supId
     * @param int $alertId
     * @param int $stationSk
     * @param string $variable
     * @param bool $primary
     */
    protected function initializationTracking(int $supId,int $alertId,int $stationSk,string $variable,bool $primary){
        $this->actualTracking->sup_id = $supId;
        $this->actualTracking->alert_id = $alertId;
        $this->actualTracking->primary_station_id = $stationSk;
        $this->actualTracking->secondary_calculate = $primary;

        $this->actualTracking->{$this->localVariable} = $this->value->{$variable};

        $this->actualTracking->alert_tag = 'green';
        $this->actualTracking->alert_level = 0;
        $this->actualTracking->alert_status = 'equal';
        $this->actualTracking->indicator_previous_difference = 0;
    }

    protected function getBeforeIndicatorTracking(){
        $this->beforeIndicatorTracking = $this->trackingTableRepository->getFromDate(
            $this->beforeDateTime,
            $this->actualTracking->sup_id,
            $this->actualTracking->alert_id,
            $this->actualTracking->primary_station_id
        );
    }

    protected function calculatePartialIndicator(){
        $this->infoIndicator = $this->trackingTableRepository->calculateIndicator(
            $this->actualTracking->date_time_initial,
            $this->actualTracking->date_time_final,
            $this->actualTracking->sup_id,
            $this->actualTracking->alert_id,
            $this->actualTracking->primary_station_id
        );
    }

    protected function calculateIndicator(){
        # Se calcula el valor del indicador
        $this->actualTracking->indicator_value = $this->actualTracking->{$this->localVariable} + $this->infoIndicator->indicator;

        # Se calcula el porcentaje de recuperados
        $this->actualTracking->rainfall_recovered = ($this->infoIndicator->recovered + 1 ) / $this->expectedAmount;
    }

    /**
     * @param float $limit
     * @param string $tag
     * @return bool
     */
    public function validateIndicator(string $tag,float $limit) : bool{
        if ($this->actualTracking->indicator_value >= $limit){
            $this->actualTracking->alert_tag = $tag;
            $this->actualTracking->alert_level = array_search($tag,$this->alertLevels);
            return true;
        }
        return false;
    }

    public function validatePreviousDeference(){
        # Se valida que si exista un valor anterior
        if (is_null($this->beforeIndicatorTracking)){return;}

        # Se valida que exista un valor de indicador calculado
        if (is_null($this->beforeIndicatorTracking->indicator_value)){return;}

        # Se calcula la diferencia de los indicadores
        $this->actualTracking->indicator_previous_difference = abs($this->actualTracking->indicator_value - $this->beforeIndicatorTracking->indicator_value);

        if ($this->actualTracking->alert_level < $this->beforeIndicatorTracking->alert_leve){
            $this->actualTracking->alert_status = 'decrease';
        } else if ($this->actualTracking->alert_level > $this->beforeIndicatorTracking->alert_level){
            $this->actualTracking->alert_status = 'increase';
        }
    }
}
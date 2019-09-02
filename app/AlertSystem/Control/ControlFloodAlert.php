<?php

namespace App\AlertSystem\Control;

use App\AlertSystem\AlertsV2\FloodAlert;
use Carbon\Carbon;

class ControlFloodAlert extends ControlAlertBase implements ControlAlertContract
{
    /**
     * @var array
     */
    protected $alerts = [];
    /**
     * ControlFloodAlert constructor.
     * @param array $primaryStations
     * @param Carbon $dateTime
     */
    public function __construct(array $primaryStations, Carbon $dateTime)
    {
       parent::__construct($primaryStations,$dateTime);
    }

    public function config()
    {
        foreach ($this->primaryStations as $primaryStation){
            $this->alerts[] = new FloodAlert($primaryStation,$this->initDateTime,$this->finalDateTime);
        }
    }

    public function execute()
    {

    }
}
<?php

namespace App\AlertSystem\ControlAlert;

use App\AlertSystem\ControlAlert\AlertContract;
use App\AlertSystem\AlertsV2\LandslideAlert;
use Carbon\Carbon;

class ControlLandslideAlert extends ControlAlertBase implements ControlAlertContract
{

    /**
     * ControlFloodAlert constructor.
     * @param array $primaryStations
     * @param Carbon $dateTime
     */
    public function __construct( Carbon $dateTime)
    {
        parent::__construct('landslide',$dateTime);
        $this->config();
    }

    public function execute()
    {
        foreach ($this->alerts as $alert){
            $alert->execute();
        }
    }

    public function config()
    {
        foreach ($this->controlAlerts as $controlAlert){
            $this->alerts[] = new LandslideAlert($this->alertCode,$controlAlert,$this->dateTime,$this->initDateTime,$this->finalDateTime);
        }
    }
}
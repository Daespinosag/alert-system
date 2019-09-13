<?php

namespace App\AlertSystem\ControlAlert;

use App\AlertSystem\AlertsV2\FloodAlert;
use Carbon\Carbon;

class ControlFloodAlert extends ControlAlertBase implements ControlAlertContract
{
    /**
     * ControlFloodAlert constructor.
     * @param Carbon $dateTime
     */
    public function __construct(Carbon $dateTime){
       parent::__construct('flood',$dateTime);

       $this->config();
    }

    public function config(){
        foreach ($this->controlAlerts as $controlAlert){
            $this->alerts[] = new FloodAlert($this->alertCode,$controlAlert,$this->dateTime,$this->initDateTime,$this->finalDateTime);
        }
    }

    public function execute(){
        foreach ($this->alerts as $alert){
            $alert->execute();
        }
    }
}
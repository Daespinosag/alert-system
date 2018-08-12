<?php

namespace App\AlertSystem\Alerts;

use App\AlertSystem\AlertSystem;

class AlertBase extends AlertSystem
{
    /**
     * @param int $value
     * @param $levels
     * @return int
     */
    public function exterminateAlert(int $value, $levels)
    {
        $alert = 0;
        foreach ($levels as $level)
        {
            if (is_null($level->maximum)){
                if ($level->minimum <= $value){
                    $alert = $level->level;
                }

            }else{
                if ($level->minimum <= $value and $level->maximum >= $value){
                    $alert  = $level->level;
                }
            }
        }
        return $alert;
    }

    public function configurationsParameters(array $parameters = [])
    {
        if (count($parameters) > 0 ){
            foreach ($parameters as $configKey => $configValue){
                if (property_exists($this, $configKey)){
                    $this->{$configKey} = $configValue;
                }else{
                    dd('ERROR: no existe la propiedad '.$configKey);
                }
            }
        }
    }

}
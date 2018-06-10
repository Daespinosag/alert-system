<?php

namespace App\AlertSystem;

use App\AlertSystem\Connection\DatabaseConfig;
use App\AlertSystem\Traits\StorageServerTrait;
use DB;


abstract class AlertSystem
{
    use DatabaseConfig,StorageServerTrait;

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



}
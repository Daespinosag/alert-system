<?php

namespace App\AlertSystem\AlertsV2;

use App\Entities\AlertSystem\ControlNewData;
use App\Repositories\Administrator\AlertFloodRepository;
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
        parent::__construct(new AlertFloodRepository(),$alertCode,'precipitacion_real',$controlNewData,$dateTime,$initDateTime,$finalDateTime);
    }

    public function execute(){
        $this->primaryStationAlert->execute($this->variableToValidate);

        dd($this);
    }
}
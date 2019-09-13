<?php

namespace App\AlertSystem\Indicators;

use App\Repositories\AlertSystem\TrackingFloodAlertRepository;

class A10MinIndicator extends IndicatorsBase implements IndicatorContract
{
    /**
     * A10MinIndicator constructor.
     * @param $value
     */
    public function __construct($value){
        parent::__construct(new TrackingFloodAlertRepository(),$value);
    }
}
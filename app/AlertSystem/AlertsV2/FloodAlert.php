<?php

namespace App\AlertSystem\AlertsV2;

use Carbon\Carbon;

class FloodAlert extends AlertBase implements AlertContract
{
    /**
     * FloodAlert constructor.
     * @param int $station
     * @param Carbon $dateTime
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime
     */
    public function __construct(int $station, Carbon $dateTime,Carbon $initDateTime,Carbon $finalDateTime)
    {
        parent::__construct($station,$dateTime,$initDateTime,$finalDateTime);
    }

    public function execute()
    {
        $this->primaryStationAlert->execute();

        dd($this);
    }
}
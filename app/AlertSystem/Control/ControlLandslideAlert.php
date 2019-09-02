<?php

namespace App\AlertSystem\Control;

use App\AlertSystem\Alerts\AlertContract;
use Carbon\Carbon;

class ControlLandslideAlert extends ControlAlertBase implements ControlAlertContract
{
    /**
     * @var AlertContract[]
     */
    protected $alertsSystems = [];

    /**
     * ControlFloodAlert constructor.
     * @param array $primaryStations
     * @param Carbon $dateTime
     */
    public function __construct(array $primaryStations, Carbon $dateTime)
    {
        parent::__construct($primaryStations,$dateTime);
    }

    public function execute()
    {

    }
}
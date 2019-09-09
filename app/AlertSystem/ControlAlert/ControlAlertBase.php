<?php

namespace App\AlertSystem\ControlAlert;

use App\AlertSystem\AlertSystem;
use App\Repositories\AlertSystem\ControlNewDataRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ControlAlertBase extends AlertSystem
{
    /**
     * @var string
     */
    public $alertCode;
    /**
     * @var Collection
     */
    protected $controlAlerts;
    /**
     * @var array
     */
    protected $alerts = [];
    /**
     * @var Carbon
     */
    protected $dateTime;
    /**
     * @var Carbon
     */
    protected $initDateTime;
    /**
     * @var Carbon
     */
    protected $finalDateTime;
    /**
     * @var ControlNewDataRepository
     */
    public $controlNewDataRepository;

    /**
     * ControlAlertBase constructor.
     * @param string $alertCode
     * @param Carbon $dateTime
     */
    public function __construct(string $alertCode, Carbon $dateTime){
        $this->controlNewDataRepository = new ControlNewDataRepository(); # TODO Esto debe ser dinamico

        $this->alertCode = $alertCode;
        $this->dateTime = $dateTime;

        $this->generateInitDateTime();
        $this->generateFinalDateTime();
        $this->getControlAlerts();
    }

    /**
     * Generate Initial DateTime | Carbon
     */
    protected function generateInitDateTime(){
        $this->initDateTime = $this->generateDateTime($this->dateTime,'-5 minutes');
    }

    /**
     * Generate Final DateTime | Carbon
     */
    protected function generateFinalDateTime(){
        $this->finalDateTime = $this->generateDateTime($this->dateTime,'+5 minutes');
    }
    /**
     * @param Carbon $dateTime
     * @param string $time
     * @return Carbon
     */
    public function generateDateTime(Carbon $dateTime,string $time) : Carbon {
        return date_add(clone ($dateTime), date_interval_create_from_date_string($time));
    }

    /**
     * Generate ControlAlerts | Collection
     */
    public function getControlAlerts(){
        $this->controlAlerts = $this->controlNewDataRepository->getUnsettledAlerts($this->alertCode);
    }
}
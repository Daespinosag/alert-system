<?php


namespace App\AlertSystem\Control;


use Carbon\Carbon;

class ControlAlertBase
{
    /**
     * @var array
     */
    protected $primaryStations;
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
     * ControlAlertBase constructor.
     * @param array $primaryStations
     * @param Carbon $dateTime
     */
    public function __construct(array $primaryStations, Carbon $dateTime)
    {
        $this->primaryStations = $primaryStations;
        $this->dateTime = $dateTime;

        $this->generateInitDateTime();
        $this->generateFinalDateTime();
    }

    /**
     * Generate Initial DateTime | Carbon
     */
    protected function generateInitDateTime()
    {
        $this->initDateTime = $this->generateDateTime($this->date,'-5 minutes');
    }

    /**
     * Generate Final DateTime | Carbon
     */
    protected function generateFinalDateTime()
    {
        $this->finalDateTime = $this->generateDateTime($this->date,'+5 minutes');
    }

    /**
     * @param Carbon $dateTime
     * @param string $time
     * @return Carbon
     */
    public function generateDateTime(Carbon $dateTime,string $time) : Carbon
    {
        return date_add(clone ($dateTime), date_interval_create_from_date_string($time));
    }
}
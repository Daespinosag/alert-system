<?php

namespace App\AlertSystem\ControlAlert;

use App\AlertSystem\AlertsV2\AlertContract;
use App\AlertSystem\AlertSystem;
use App\Helpers\Log;
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
     * @var AlertContract[]
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
     * @var config test by date
     */
    public $config = null;

    /**
     * ControlAlertBase constructor.
     * @param string $alertCode
     * @param Carbon $dateTime
     */
    public function __construct(string $alertCode, Carbon $dateTime, $config = null)
    {
        $this->controlNewDataRepository = new ControlNewDataRepository(); # TODO Esto debe ser dinamico

        $this->alertCode = $alertCode;
        $this->dateTime = $dateTime;
        $this->config = $config;
        $this->generateInitDateTime();
        $this->generateFinalDateTime();
        $this->controlAlerts = $this->getControlAlerts();

    }

    /**
     * Generate Initial DateTime | Carbon
     */
    protected function generateInitDateTime()
    {
        $this->initDateTime = $this->generateDateTime($this->dateTime, '-5 minutes');
    }

    /**
     * Generate Final DateTime | Carbon
     */
    protected function generateFinalDateTime()
    {
        $this->finalDateTime = $this->generateDateTime($this->dateTime, '+5 minutes');
    }

    /**
     * @param Carbon $dateTime
     * @param string $time
     * @return Carbon
     */
    public function generateDateTime(Carbon $dateTime, string $time): Carbon
    {
        return date_add(clone($dateTime), date_interval_create_from_date_string($time));
    }

    /**
     * Generate ControlAlerts | Collection
     */
    public function getControlAlerts()
    {


        try {
            if (!is_null($this->config)) {
                if (count($this->config[$this->alertCode]) > 0) {
                    return $this->controlNewDataRepository->getUnsettledAlertsSpecific($this->alertCode, $this->config[$this->alertCode]);
                }
            }
            return $this->controlAlerts = $this->controlNewDataRepository->getUnsettledAlerts($this->alertCode);
        } catch (Exception $e) {
            Log::newError('ControlAlertBase', 'Max', 'AlertSystem|ControlAlert|ControlAlertBase|getControlAlerts|No pudo recuperar los datos', $e, [$this->alertCode, $this->config[$this->alertCode]]);
        }

    }
}
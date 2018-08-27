<?php

namespace App\AlertSystem\Alerts;

use App\Events\AlertFiveMinutesCalculated;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\LandslideRepository;
use App\Repositories\Administrator\ConnectionRepository;
use Carbon\Carbon;

class LandslideAlert extends AlertBase implements AlertInterface
{
    public $connectionRepository;

    public $stationRepository;

    public $landslideRepository;

    public $alertRepository;

    public $constData = 7200;

    public $constDays = 24;

    public $externalConnection = 'external_connection_alert_system';

    public $sendEmail = true;

    public $sendEventData = false;

    public $insertDatabase = true;

    public $initialDate = null;

    public $finalDate = null;

    public $stations = null;

    public $levels = null;

    public $datesRangesSearch = [];

    public $values = [];

    /**
     * AlertSystem constructor.
     * @param ConnectionRepository $connectionRepository
     * @param StationRepository $stationRepository
     * @param LandslideRepository $landslideRepository
     * @param AlertRepository $alertRepository
     * @param array $configurations
     */
    public function  __construct(
        ConnectionRepository $connectionRepository,
        StationRepository $stationRepository,
        LandslideRepository $landslideRepository,
        AlertRepository $alertRepository,
        array $configurations = []
    )
    {
        $this->connectionRepository = $connectionRepository;
        $this->stationRepository = $stationRepository;
        $this->alertRepository = $alertRepository;
        $this->landslideRepository = $landslideRepository;

        $this->configurationsParameters($configurations,'alert-a25');
    }

    public function init()
    {
        $this->processAlert(
            $this->connectionRepository,
            $this->landslideRepository,
            'calculateA25',
            'exterminateAlert',
            'precipitacion_real'
        );

        if ($this->insertDatabase){ $this->createInAlertSpecificTable($this->landslideRepository);}

        if ($this->sendEventData){
            # TODO enviar evento
            // event(new AlertFiveMinutesCalculated($arrayNewValues));
        }

        return $this;
    }

    /**
     * @param Carbon $initialDate
     * @param Carbon $finalDate
     */
    public function configureDatesToSearch(Carbon $initialDate, Carbon $finalDate)
    {
        $flag = true;
        $initial = $this->standardizationDate($initialDate);
        $final = $this->standardizationDate($finalDate);

        while ($flag){
            $temporalAnt= (clone($initial))->addDay(- $this->constDays);

            array_push($this->datesRangesSearch,[
                'date_execute'  => clone($initial),
                'finalDate'     => $initial->format('Y-m-d'),
                'initialDate'   => $temporalAnt->format('Y-m-d'),
                'finalTime'     => (clone($initial))->addSeconds( -1 )->format('H:i:s'),
                'initialTime'   => $initial->format('H:i:s'),
            ]);

            $flag = (boolean)($final->greaterThan($initial));

            $initial->addSeconds(300);
        }
    }
}
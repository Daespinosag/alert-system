<?php

namespace App\AlertSystem\Alerts;

use App\Repositories\Administrator\AlertRepository;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\FloodRepository;
use Carbon\Carbon;

class FloodAlert extends AlertBase implements AlertInterface
{
    /**
     * @var ConnectionRepository
     */
    public $connectionRepository;
    /**
     * @var StationRepository
     */
    public $stationRepository;
    /**
     * @var FloodRepository
     */
    public $floodRepository;
    /**
     * @var AlertRepository
     */
    public $alertRepository;

    public $constSeconds = 600;

    public $constData = 2;

    public $externalConnection = 'external_connection_alert_system';

    public $sendEmail = true;

    public $sendEventData = false;

    public $insertDatabase = true;

    public $initialDate = null;

    public $finalDate = null;

    public $stations = null;

    public $datesRangesSearch = [];

    public $values = [];

    /**
     * flood constructor.
     * @param ConnectionRepository $connectionRepository
     * @param StationRepository $stationRepository
     * @param FloodRepository $floodRepository
     * @param AlertRepository $alertRepository
     * @param $configurations
     */
    public function __construct
    (
        ConnectionRepository $connectionRepository,
        StationRepository $stationRepository,
        FloodRepository $floodRepository,
        AlertRepository $alertRepository,
        array $configurations = []
    )
    {
        $this->connectionRepository = $connectionRepository;
        $this->stationRepository = $stationRepository;
        $this->floodRepository = $floodRepository;
        $this->alertRepository = $alertRepository;

        $this->configurationsParameters($configurations,'alert-a10');
    }

    /**
     * @return mixed
     */
    public function init()
    {
        $this->processAlert(
            $this->connectionRepository,
            $this->floodRepository,
            'calculateA10',
            'exterminateFloodAlert',
            'precipitacion_real'
        );

        if ($this->insertDatabase){ $this->createInAlertSpecificTable($this->floodRepository);}

        if ($this->sendEventData){
            # TODO enviar evento
        }

        return $this;
    }


    /**
     * @param $station
     * @param null $alertValue
     * @return int
     */
    public function exterminateFloodAlert($station, $alertValue = null)
    {
        $value = 0;

        if (!is_null($alertValue))
        {
            if ($alertValue >= $station->flag_level_three){
                $value = 3;
            }else if ($alertValue >= $station->flag_level_two){
                $value = 2;
            }else if ($alertValue >= $station->flag_level_one){
                $value = 1;
            }
        }

        return $value;
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
            $temporalAnt= (clone($initial))->addSeconds( - $this->constSeconds);

            array_push($this->datesRangesSearch,[
                'date_execute'  => clone($initial),
                'finalDate'     => $initial->format('Y-m-d'),
                'initialDate'   => $temporalAnt->format('Y-m-d'),
                'finalTime'     => (clone($initial))->addSeconds( -1 )->format('H:i:s'),
                'initialTime'   => $temporalAnt->format('H:i:s'),
            ]);

            $flag = (boolean)($final->greaterThan($initial));

            $initial->addSeconds(300);
        }
    }
}
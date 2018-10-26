<?php

namespace App\AlertSystem\Alerts;

use App\Repositories\Administrator\AlertRepository;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\FloodRepository;
use App\Repositories\AlertSystem\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class FloodAlert extends AlertBase implements AlertInterface
{
    /**
     * @var string
     */
    public $code = 'a10';

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

    /**
     * @var int
     */
    public $constSeconds = 600;

    /**
     * @var int
     */
    public $constData = 2;

    /**
     * @var string
     */
    public $externalConnection = 'external_connection_alert_system';

    /**
     * @var bool
     */
    public $sendEmail = false;

    /**
     * @var bool
     */
    public $sendEmailChanges = true;

    /**
     * @var bool
     */
    public $sendEventData = false;

    /**
     * @var bool
     */
    public $sendEventDataChanges = false;

    /**
     * @var bool
     */
    public $insertDatabase = true;

    /**
     * @var Carbon
     */
    public $initialDate = null;

    /**
     * @var Carbon
     */
    public $finalDate = null;

    /**
     * @var Collection
     */
    public $stations = null;

    /**
     * @var array
     */
    public $datesRangesSearch = [];

    /**
     * @var array
     */
    public $values = [];

    /**
     * @var array
     */
    public $valuesChangeAlert = [];

    /**
     * @var string
     */
    public $dateExecution = '#';

    /**
     * @var int
     */
    public $temporalMultiplication = 10;  # TODO esto se quita cuando terminen las pruebas
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * flood constructor.
     * @param UserRepository $userRepository
     * @param ConnectionRepository $connectionRepository
     * @param StationRepository $stationRepository
     * @param FloodRepository $floodRepository
     * @param AlertRepository $alertRepository
     * @param array $configurations
     */
    public function __construct
    (
        UserRepository $userRepository,
        ConnectionRepository $connectionRepository,
        StationRepository $stationRepository,
        FloodRepository $floodRepository,
        AlertRepository $alertRepository,
        array $configurations = []
    )
    {
        $this->userRepository = $userRepository;
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

        if ($this->sendEmailChanges){
            $this->sendChangesEmail(
                $this->userRepository,
                $this->code,
                'Alerta por Inundación',
                '(test) Cambio Indicadores Inundación ('.$this->dateExecution.') - ('.Carbon::now()->format('Y-m-d H:i:s').')'
            );
        }

        if ($this->sendEmail){
            # TODO
        }

        if ($this->sendEventDataChanges){
            # TODO enviar evento
        }

        if ($this->sendEventData){ $this->sendEventDataAB();}

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

        # se calcula la fecha inicial para enviarlo en es estado del correo
        $this->dateExecution = (clone($initialDate))->format('Y-m-d H:i:s');

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
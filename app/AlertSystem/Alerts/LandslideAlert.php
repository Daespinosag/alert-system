<?php

namespace App\AlertSystem\Alerts;

use App\Repositories\Administrator\AlertRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\LandslideRepository;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\AlertSystem\UserRepository;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class LandslideAlert extends AlertBase implements AlertInterface
{
    /**
     * @var string
     */
    public $code = 'a25';

    /**
     * @var ConnectionRepository
     */
    public $connectionRepository;

    /**
     * @var StationRepository
     */
    public $stationRepository;

    /**
     * @var LandslideRepository
     */
    public $landslideRepository;

    /**
     * @var AlertRepository
     */
    public $alertRepository;

    /**
     * @var int
     */
    public $constData = 7200;

    /**
     * @var int
     */
    public $constDays = 24;

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
     * @var Collection
     */
    public $levels = null;

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
    public $temporalMultiplication = 10; # TODO esto se quita cuando terminen las pruebas

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AlertSystem constructor.
     * @param UserRepository $userRepository
     * @param ConnectionRepository $connectionRepository
     * @param StationRepository $stationRepository
     * @param LandslideRepository $landslideRepository
     * @param AlertRepository $alertRepository
     * @param array $configurations
     */
    public function  __construct(
        UserRepository $userRepository,
        ConnectionRepository $connectionRepository,
        StationRepository $stationRepository,
        LandslideRepository $landslideRepository,
        AlertRepository $alertRepository,
        array $configurations = []
    )
    {
        $this->userRepository = $userRepository;
        $this->connectionRepository = $connectionRepository;
        $this->stationRepository = $stationRepository;
        $this->alertRepository = $alertRepository;
        $this->landslideRepository = $landslideRepository;

        $this->configurationsParameters($configurations,'alert-a25');
    }

    public function init()
    {
        # Se ejecuta el proceso de calculo de la alerta
        $this->processAlert(
            $this->connectionRepository,
            $this->landslideRepository,
            'calculateA25',
            'exterminateAlert',
            'precipitacion_real'
        );

        # Se envia un email con las alertas por estaciones que presentaron algun cambio
        if ($this->sendEmailChanges)
        {
            $this->sendChangesEmail(
                $this->userRepository,
                $this->code,
                'Alerta por Deslizamiento',
                '(test) Cambio Indicadores Deslizamiento ('.$this->dateExecution.') - ('.Carbon::now()->format('Y-m-d H:i:s').')'
            );
        }



        # Se envia un email con las alertas por estacion
        if ($this->sendEmail){
            # TODO
        }

        # Se envia un evento con las aleras por estaciones que cambian
        if ($this->sendEventDataChanges){
            # TODO enviar evento
        }

        # Se encia un evento con las alertas por estacion
        if ($this->sendEventData){ $this->sendEventDataAB(); }

        # Se inserta en la base de datos la alerta
        if ($this->insertDatabase){ $this->createInAlertSpecificTable($this->landslideRepository);}

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

        # se calcula la fecha inicial para enviarlo en es estado del correo
        $this->dateExecution = (clone($initialDate))->format('Y-m-d H:i:s');

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
<?php

namespace App\AlertSystem\Alerts;

use App\Events\AlertFiveMinutesCalculated;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\LandslideRepository;
use App\Repositories\Administrator\ConnectionRepository;
use Carbon\Carbon;
use Mail;

class LandslideAlert extends AlertBase implements AlertInterface
{
    public $code = 'a25';

    public $connectionRepository;

    public $stationRepository;

    public $landslideRepository;

    public $alertRepository;

    public $constData = 7200;

    public $constDays = 24;

    public $externalConnection = 'external_connection_alert_system';

    public $sendEmail = false;

    public $sendEmailChanges = true;

    public $sendEventData = false;

    public $sendEventDataChanges = false;

    public $insertDatabase = true;

    public $initialDate = null;

    public $finalDate = null;

    public $stations = null;

    public $levels = null;

    public $datesRangesSearch = [];

    public $values = [];

    public $valuesChangeAlert = [];

    public $dateExecution = '#';

    public $temporalMultiplication = 10; # TODO esto se quita cuando terminen las pruebas

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
        # Se ejecuta el proceso de calculo de la alerta
        $this->processAlert(
            $this->connectionRepository,
            $this->landslideRepository,
            'calculateA25',
            'exterminateAlert',
            'precipitacion_real'
        );

        # Se envia un email con las alertas por estaciones que presentaron algun cambio
        if ($this->sendEmailChanges){
            $data = $this->getAlertsDefences();

            if ($data->changes){
                Mail::to('ideaalertas@gmail.com')
                    ->bcc(['daespinosag@unal.edu.co'])#,'acastillorua@unal.edu.co','jdzambranona@unal.edu.co','fmejiaf@unal.edu.co'
                    ->send(new \App\Mail\TestEmail(
                        'Alerta por Deslizamiento',
                        $data,
                        '(test) Cambio Indicadores Deslizamiento ('.$this->dateExecution.') - ('.Carbon::now()->format('Y-m-d H:i:s').')',
                        $this->code
                    ));
            }
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
        if ($this->sendEventData){
            // event(new AlertFiveMinutesCalculated($arrayNewValues));
        }

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
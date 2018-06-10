<?php

namespace App\AlertSystem\Alerts;

use App\AlertSystem\AlertSystem;
use App\Events\AlertFiveMinutesCalculated;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\A25FiveMinutesRepository;
use App\Repositories\Administrator\ConnectionRepository;
use Carbon\Carbon;

class AlertA25 extends AlertSystem implements AlertInterface
{
    private $connectionRepository;

    private $stationRepository;

    private $a25FiveMinutesRepository;

    public $constData = 7200;

    public $constDays = 24;

    public $externalConnection = 'external_connection_alert_system';
    /**
     * @var AlertRepository
     */
    private $alertRepository;

    /**
     * AlertSystem constructor.
     * @param ConnectionRepository $connectionRepository
     * @param StationRepository $stationRepository
     * @param A25FiveMinutesRepository $a25FiveMinutesRepository
     * @param AlertRepository $alertRepository
     */
    public function  __construct(
        ConnectionRepository $connectionRepository,
        StationRepository $stationRepository,
        A25FiveMinutesRepository $a25FiveMinutesRepository,
        AlertRepository $alertRepository
    )
    {
        $this->connectionRepository = $connectionRepository;
        $this->stationRepository = $stationRepository;
        $this->a25FiveMinutesRepository = $a25FiveMinutesRepository;
        $this->alertRepository = $alertRepository;
    }

    public function init()
    {
        # Consultar las estaciones que tienen registrada y activa la alerta de a25
        $stations = $this->stationRepository->getForAlertSystem('alert-a25');

        $actualDateTime = Carbon::now();
        $dateTwo = $actualDateTime->format('Y-m-d');
        $dateOne = $actualDateTime->addDay(-$this->constDays)->format('Y-m-d');
        $time = $actualDateTime->format('H:i:s');

        # Consultar los diferentes niveles de la alerta a25
        $alertLevels = $this->alertRepository->getLevelAlert('alert-a25');

        $arrayNewValues = [];

        foreach ($stations as $station)
        {
            # se crea un nuevo modelo para la tabla a25FiveMinutes
            $showcaseA25Table = $this->a25FiveMinutesRepository->createShowcase();

            # Se extrae el ultimo valor de la tabla a25 para una estacion especifica
            $ultimateDateA25 = $this->a25FiveMinutesRepository->getUltimateDate($station->id);

            # Se consulta la conexion perteneciente a la estacion
            $connection = $this->connectionRepository->findOrFail($station->connection_id);

            # Se busca la tabla en la central de acopio  y se crea la coneccion
            $resultConnection = $this->searchExternalConnection($connection,$station->table_db_name,$this->externalConnection);

            if ($resultConnection){

                # Se consultan los datos de a25 en la central de acopio
                $result = $this->calculateA25($this->externalConnection,$station->table_db_name,$dateOne,$dateTwo,$time);

                $showcaseA25Table->station = $station->id;

                if (!is_null($result->a25)){
                    $showcaseA25Table->a25_value = $result->a25;

                    # Se examina el nivel de alerta
                    $showcaseA25Table->alert = $this->exterminateAlert($result->a25,$alertLevels);
                }

                $showcaseA25Table->avg_recovered = round ($result->count / $this->constData * 100,2);

                if (!is_null($ultimateDateA25)){
                    $showcaseA25Table->dif_previous_a25 = abs(round($ultimateDateA25->a25_value - $showcaseA25Table->a25_value,2));

                    if ($showcaseA25Table->alert == $ultimateDateA25->alert){
                        $showcaseA25Table->num_not_change_alert = $ultimateDateA25->num_not_change_alert + 1;
                    }else{
                        $showcaseA25Table->num_not_change_alert = 0;
                        $showcaseA25Table->change_alert = true;

                        if ($showcaseA25Table->alert < $ultimateDateA25->alert){ $showcaseA25Table->alert_decrease = true;}
                        else{$showcaseA25Table->alert_increase = true;}
                    }
                }

                $value = $this->a25FiveMinutesRepository->create($showcaseA25Table->toArray());
                array_push($arrayNewValues,$value);
            }
        }
        dd($arrayNewValues);

        event(new AlertFiveMinutesCalculated($arrayNewValues));
    }

}
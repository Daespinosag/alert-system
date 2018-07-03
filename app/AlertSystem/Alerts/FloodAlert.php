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
    private $connectionRepository;
    /**
     * @var StationRepository
     */
    private $stationRepository;
    /**
     * @var FloodRepository
     */
    private $floodRepository;
    /**
     * @var AlertRepository
     */
    private $alertRepository;

    public $constSeconds = 600;

    public $constData = 2;

    public $externalConnection = 'external_connection_alert_system';

    /**
     * flood constructor.
     * @param ConnectionRepository $connectionRepository
     * @param StationRepository $stationRepository
     * @param FloodRepository $floodRepository
     * @param AlertRepository $alertRepository
     */
    public function __construct
    (
        ConnectionRepository $connectionRepository,
        StationRepository $stationRepository,
        FloodRepository $floodRepository,
        AlertRepository $alertRepository
    )
    {
        $this->connectionRepository = $connectionRepository;
        $this->stationRepository = $stationRepository;
        $this->floodRepository = $floodRepository;
        $this->alertRepository = $alertRepository;
    }

    /**
     * @return mixed
     */
    public function init()
    {
        # Consultar las estaciones que tienen registrada y activa la alerta de inundacion
        $stations = $this->stationRepository->getForAlertSystem('alert-a10');

        $actualDateTime = Carbon::now();
        $antDateTime = (clone($actualDateTime))->addSeconds(-$this->constSeconds);
        $actualDate = $actualDateTime->format('Y-m-d');
        $antDate = $antDateTime->format('Y-m-d');
        $actualTime = $actualDateTime->format('H:i:s');
        $antTime = $antDateTime->format('H:i:s');

        $arrayNewValues = [];

        foreach ($stations as $station)
        {
            # se crea un nuevo modelo para la tabla a25FiveMinutes
            $floodTable = $this->floodRepository->createShowcase();

            # Se extrae el ultimo valor de la tabla a10 para una estacion especifica
            $ultimateDateFlood = $this->floodRepository->getUltimateDate($station->id);

            # Se consulta la conexion perteneciente a la estacion
            $connection = $this->connectionRepository->findOrFail($station->connection_id);

            # Se busca la tabla en la central de acopio  y se crea la coneccion
            $resultConnection = $this->searchExternalConnection($connection,$station->table_db_name,$this->externalConnection);

            if ($resultConnection){
                # Se consultan los datos de a25 en la central de acopio
                $result = $this->calculateA10($this->externalConnection,$station->table_db_name,$antDate,$antTime,$actualDate,$actualTime);

                $floodTable->station = $station->id;

                if (!is_null($result->a10)){
                    $floodTable->a10_value = $result->a10;

                    # Se examina el nivel de alerta
                    $floodTable->alert = $this->exterminateFloodAlert($station,$result->a10);
                }

                $floodTable->avg_recovered = round ($result->count / $this->constData * 100,2);

                if (!is_null($ultimateDateFlood)){
                    $floodTable->dif_previous_a10 = abs(round($ultimateDateFlood->a10_value - $floodTable->a10_value,2));

                    if ($floodTable->alert == $ultimateDateFlood->alert){
                        $floodTable->num_not_change_alert = $ultimateDateFlood->num_not_change_alert + 1;
                    }else{
                        $floodTable->num_not_change_alert = 0;
                        $floodTable->change_alert = true;

                        if ($floodTable->alert < $ultimateDateFlood->alert){ $floodTable->alert_decrease = true;}
                        else{$floodTable->alert_increase = true;}
                    }
                }
                $value = $this->floodRepository->create($floodTable->toArray());
                array_push($arrayNewValues,$value);
            }
        }
        //dd($arrayNewValues);
        # TODO enviar evento
    }


    public function exterminateFloodAlert($station,$alertValue = null)
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
}
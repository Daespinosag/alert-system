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

        $this->configurationsParameters($configurations);
    }

    /**
     * @return mixed
     */
    public function init()
    {
        # Consultar las estaciones que tienen registrada y activa la alerta de inundacion
        $this->stations = $this->stationRepository->getForAlertSystem('alert-a10', $this->stations);

        # Se configuran los espacios cincominutales a calcular en la
        $this->configureDatesToSearch(
            (is_null($this->initialDate)) ?  Carbon::now() : $this->initialDate,
            (is_null($this->finalDate)) ?  Carbon::now() : $this->finalDate
        );

        $this->processAlertA10();

        if ($this->insertDatabase){
            foreach ($this->values as $value){ $this->floodRepository->create($value); }
        }

        if ($this->sendEventData){
            # TODO enviar evento
        }

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
     * @param Carbon $date
     * @return Carbon|static
     */
    public function standardizationDate(Carbon $date)
    {
        $residue = $date->minute % 5;

        $result = ($residue == 0) ? $date : $date->addSeconds( ((5 - $residue) * 60 ));
        $result->second = 0;

        return $result;
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

    /**
     * @param $station
     * @param array $dateSearch
     * @param array $values
     * @param null $ultimateDateFlood
     * @return mixed
     */
    public function generateStatistics($station, array $dateSearch, array $values, $ultimateDateFlood = null)
    {
        # se crea un nuevo modelo para la tabla a25FiveMinutes
        $floodTable = $this->floodRepository->createShowcase();

        $floodTable->station = $station->id;

        $floodTable->date_execution = $dateSearch['date_execute']->format('Y-m-d H:i:s');

        $finalValue = array_values($values)[0];
        $floodTable->date_final = (!is_null($finalValue)) ? $finalValue->fecha.' '.$finalValue->hora : null;

        $initialValue = end($values);
        $floodTable->date_initial = (!is_null($initialValue)) ? $initialValue->fecha.' '.$initialValue->hora : null;

        $floodTable->a10_value = array_sum(array_column($values, 'precipitacion_real'));

        $floodTable->avg_recovered = round (count($values) / $this->constData * 100,2);

        $floodTable->alert = $this->exterminateFloodAlert($station,$floodTable->a10_value);

        $floodTable->dif_previous_a10 = null;
        $floodTable->num_not_change_alert = $ultimateDateFlood->num_not_change_alert + 1;
        $floodTable->change_alert = false;
        $floodTable->alert_decrease = false;
        $floodTable->alert_increase = false;

        if (!is_null($ultimateDateFlood)) {

            $floodTable->dif_previous_a10 = abs(round($ultimateDateFlood->a10_value - $floodTable->a10_value, 2));

            if (!($floodTable->alert == $ultimateDateFlood->alert)) {

                $floodTable->num_not_change_alert = 0;
                $floodTable->change_alert = true;

                if ($floodTable->alert < $ultimateDateFlood->alert) {
                    $floodTable->alert_decrease = true;
                } else {
                    $floodTable->alert_increase = true;
                }
            }
        }

        return $floodTable;
    }

    /**
     *
     */
    public function processAlertA10()
    {
        foreach ($this->stations as $station)
        {
            # Se consulta la conexion perteneciente a la estacion
            $connection = $this->connectionRepository->findOrFail($station->connection_id);

            # Se busca la tabla en la central de acopio  y se crea la coneccion
            $resultConnection = $this->searchStaticConnection($connection->name,$station->table_db_name);

            foreach ($this->datesRangesSearch as $dateSearch)
            {
                # Se extrae el ultimo valor de la tabla a10 para una estacion especifica
                $ultimateDateFlood = $this->floodRepository->getUltimateDate($station->id, $dateSearch['date_execute']->format('Y-m-d H:i:s'));

                if ($resultConnection)
                {
                    # Se consultan los datos de a10 en la central de acopio
                    $result = $this->calculateA10($resultConnection,$station->table_db_name,$dateSearch['initialDate'],$dateSearch['initialTime'],$dateSearch['finalDate'],$dateSearch['finalTime']);

                    if (!is_null($result)){
                        array_push($this->values,$this->generateStatistics($station,$dateSearch,$result,$ultimateDateFlood)->toArray());
                    }
                }
            }
        }
    }


}
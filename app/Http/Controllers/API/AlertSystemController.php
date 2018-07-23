<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\Administrator\NetRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\LandslideRepository;
use App\Repositories\Administrator\StationTypeRepository;

class AlertSystemController extends Controller
{
    /**
     * @var StationRepository
     */
    private $stationRepository;
    /**
     * @var NetRepository
     */
    private $netRepository;
    /**
     * @var LandslideRepository
     */
    private $a25FiveMinutesRepository;
    /**
     * @var AlertRepository
     */
    private $alertRepository;
    /**
     * @var StationTypeRepository
     */
    private $stationTypeRepository;

    /**
     * AlertSystemController constructor.
     * @param StationRepository $stationRepository
     * @param NetRepository $netRepository
     * @param LandslideRepository $a25FiveMinutesRepository
     * @param AlertRepository $alertRepository
     * @param StationTypeRepository $stationTypeRepository
     */
    public function __construct(
        StationRepository $stationRepository,
        NetRepository $netRepository,
        LandslideRepository $a25FiveMinutesRepository,
        AlertRepository $alertRepository,
        StationTypeRepository $stationTypeRepository
    )
    {
        $this->stationRepository = $stationRepository;
        $this->netRepository = $netRepository;
        $this->a25FiveMinutesRepository = $a25FiveMinutesRepository;
        $this->alertRepository = $alertRepository;
        $this->stationTypeRepository = $stationTypeRepository;
    }

    /**
     * @return mixed
     */
    public function getStations()
    {
        $possibleAlert = ['alert-a25','alert-a10']; # TODO esto debe entrar por parametro dependiando de las alertas permitidas para un usuario

        $stations = $this->stationRepository->getStationsFromAlertsForMaps($possibleAlert);

        foreach ($stations as $station)
        {
            $station->maximumAlert = 0;
            $station->dataMaximumAlert = null;

            $station->longitude = $this->calculateDecimalCoordinates(
                $station->longitude_degrees,
                $station->longitude_minutes,
                $station->longitude_seconds,
                $station->longitude_direction
            );
            $station->latitude = $this->calculateDecimalCoordinates(
                $station->latitude_degrees,
                $station->latitude_minutes,
                $station->latitude_seconds,
                $station->latitude_direction
            );

            foreach ($station->alerts as $alert){

                # TODO ESTO HAY QYE ARREGLARLO PARA QUE SAQUE EL ULTIMO PERO SOLO DE LOS ULTIMOS 5 MINUTOS
                $alert->value = $this->stationRepository->getUltimateDataInAlertTable($alert->table,$station->id);

                if (!is_null($alert->value)){
                    if (!is_null($alert->value->alert)){
                        if ($alert->value->alert >= $station->maximumAlert){
                            $station->maximumAlert = $alert->value->alert;
                            $station->dataMaximumAlert = $alert;
                        }
                    }
                }
            }
        }

        return $stations->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getStation($id)
    {
       $station = $this->stationRepository->getStation($id);

        $station->longitude = $this->calculateDecimalCoordinates(
            $station->longitude_degrees,
            $station->longitude_minutes,
            $station->longitude_seconds,
            $station->longitude_direction
        );
        $station->latitude = $this->calculateDecimalCoordinates(
            $station->latitude_degrees,
            $station->latitude_minutes,
            $station->latitude_seconds,
            $station->latitude_direction
        );

        return $station;

    }

    /**
     * @return mixed
     */
    public function getNets()
    {
        return $this->netRepository->getNets();
    }


    public function getAlerts()
    {
        return $this->alertRepository->getAlerts();
    }

    public function getTypeStation()
    {
        $possibility = ['M','H','PM','PG']; #Aca se definen las posibles consultas de estaciones de clima.

        return $this->stationTypeRepository->getTypeStations($possibility);
    }

    /**
     * @param $degrees
     * @param $minutes
     * @param $seconds
     * @param $direction
     * @return float
     */
    private function calculateDecimalCoordinates($degrees, $minutes, $seconds, $direction)
    {
        $val = ($direction == 'W' or $direction == 'S') ? -1 : 1;

        return round(( ( $degrees + ( $minutes / 60 ) + ( $seconds / 3600 )) * $val ) , 6);
    }

}
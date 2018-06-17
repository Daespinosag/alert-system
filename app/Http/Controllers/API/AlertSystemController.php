<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Administrator\NetRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\A25FiveMinutesRepository;

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
     * @var A25FiveMinutesRepository
     */
    private $a25FiveMinutesRepository;

    /**
     * AlertSystemController constructor.
     * @param StationRepository $stationRepository
     * @param NetRepository $netRepository
     * @param A25FiveMinutesRepository $a25FiveMinutesRepository
     */
    public function __construct(
        StationRepository $stationRepository,
        NetRepository $netRepository,
        A25FiveMinutesRepository $a25FiveMinutesRepository
    )
    {
        $this->stationRepository = $stationRepository;
        $this->netRepository = $netRepository;
        $this->a25FiveMinutesRepository = $a25FiveMinutesRepository;
    }

    /**
     * @return mixed
     */
    public function getStations()
    {
        $stations =  $this->stationRepository->getStationsForMaps('alert-a25');

        foreach ($stations as $station){
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

            # TODO ESTO HAY QYE ARREGLARLO PARA QUE SAQUE EL ULTIMO PERO SOLO DE LOS ULTIMOS 5 MINUTOS
            $station->alertA25 = $this->a25FiveMinutesRepository->getUltimateDate($station->id);
            $station->alertInundation = true;
            $station->alertLandslide = true;

        }

        return $stations;
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
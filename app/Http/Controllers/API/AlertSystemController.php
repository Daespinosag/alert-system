<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\Administrator\NetRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\FloodRepository;
use App\Repositories\AlertSystem\LandslideRepository;
use App\Repositories\Administrator\StationTypeRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AlertSystem\Alerts\FloodAlert;
use App\AlertSystem\Alerts\LandslideAlert;
use Illuminate\Support\Facades\Auth;

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
     * @var ConnectionRepository
     */
    private $connectionRepository;
    /**
     * @var FloodRepository
     */
    private $floodRepository;
    /**
     * @var LandslideRepository
     */
    private $landslideRepository;

    /**
     * AlertSystemController constructor.
     * @param StationRepository $stationRepository
     * @param NetRepository $netRepository
     * @param LandslideRepository $a25FiveMinutesRepository
     * @param AlertRepository $alertRepository
     * @param StationTypeRepository $stationTypeRepository
     * @param ConnectionRepository $connectionRepository
     * @param FloodRepository $floodRepository
     * @param LandslideRepository $landslideRepository
     */
    public function __construct(
        StationRepository $stationRepository,
        NetRepository $netRepository,
        LandslideRepository $a25FiveMinutesRepository,
        AlertRepository $alertRepository,
        StationTypeRepository $stationTypeRepository,
        ConnectionRepository $connectionRepository,
        FloodRepository $floodRepository,
        LandslideRepository $landslideRepository
    )
    {
        $this->stationRepository = $stationRepository;
        $this->netRepository = $netRepository;
        $this->a25FiveMinutesRepository = $a25FiveMinutesRepository;
        $this->alertRepository = $alertRepository;
        $this->stationTypeRepository = $stationTypeRepository;
        $this->connectionRepository = $connectionRepository;
        $this->floodRepository = $floodRepository;
        $this->landslideRepository = $landslideRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getStations(Request $request)
    {
        $possibleAlert = array_column($request->get('alerts'),'code');

        $stations = $this->stationRepository->getStationsFromAlertsForMaps($possibleAlert);
        $position = 0;

        foreach ($stations as $station)
        {
            $station->maximumAlert = 0;
            $station->dataMaximumAlert = null;
            $station->show = true;
            $station->position = $position;

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

            $temporalAlert = -1;

            foreach ($station->alerts as $alert)
            {
                if ($alert->active){

                    # TODO ESTO HAY QYE ARREGLARLO PARA QUE SAQUE EL ULTIMO PERO SOLO DE LOS ULTIMOS 5 MINUTOS
                    $alert->value = $this->stationRepository->getUltimateDataInAlertTable($alert->table,$station->id);

                    if (!is_null($alert->value)){
                        if (!is_null($alert->value->alert)){
                            if ($alert->value->alert >= $station->maximumAlert){
                                $station->alertMax = $alert->value->alert;
                                $station->dataMaximumAlert = $alert;
                                $station->iconMax = $alert->icon;

                                if (!$alert->value->error){
                                    if ((int)$alert->value->alert > $temporalAlert){
                                        $temporalAlert = (int)$alert->value->alert;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $station->alertMax = $temporalAlert;
            $station->color = $this->getStationColor($temporalAlert);

            $position++;
        }

        return $stations->toArray();
    }

    public function getStationColor(int $temporalAlert)
    {
        if ($temporalAlert == 0){ return 'green';}
        if ($temporalAlert == 1){ return 'yellow';}
        if ($temporalAlert == 2){ return 'orange';}
        if ($temporalAlert == 3){ return 'red';}

        return 'gray';
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


    public function getAlerts(Request $request)
    {
        $permissions =  $request->get('permissions');
        $arr = [];

        foreach ($permissions as $permission){
            if ($permission['pivot']['active'] and $permission['type'] == 'permission-alert'){
                array_push($arr, str_replace("permission-","alert-",$permission['code']) );
            }
        }

        return (count($arr) > 0 ) ? $this->alertRepository->getAlertsWhereIn($arr) : null ;
    }

    public function getTypeStation()
    {
        $possibility = ['M','H']; #Aca se definen las posibles consultas de estaciones de clima.

        return $this->stationTypeRepository->getTypeStations($possibility);
    }

    public function consultAlert(Request $request)
    {
        $object = null;
        $initialDate = Carbon::parse($request->get('initialDate'))->format('Y-m-d');
        $finalDate = Carbon::parse($request->get('finalDate'))->format('Y-m-d');

        $configurations = [
            'sendEmail'         => false,
            'insertDatabase'    => false,
            'sendEventData'     => false,
            'initialDate'       => Carbon::parse($initialDate.' 00:00:00'),
            'finalDate'         => Carbon::parse($finalDate.' 23:55:00'),
            'stations'          => [$request->get('station')]
        ];


        if ($request->get('alert') == 'alert-a10'){

            $object = (new FloodAlert(
                $this->connectionRepository,
                $this->stationRepository,
                $this->floodRepository,
                $this->alertRepository,
                $configurations
            ))->init()->values;
        }

        if ($request->get('alert') == 'alert-a25'){
            $object = (new LandslideAlert(
                $this->connectionRepository,
                $this->stationRepository,
                $this->landslideRepository,
                $this->alertRepository,
                $configurations
            ))->init()->values;
        }

        $columns = [
            ['prop'=> 'station', 'label'=> 'station' ],
            ['prop'=> 'date_execution', 'label'=> 'date_execution' ],
            ['prop'=> 'date_final', 'label'=> 'date_final' ],
            ['prop'=> 'date_initial', 'label'=> 'date_initial' ],
            ['prop'=> 'a10_value', 'label'=> 'a10_value' ],
            ['prop'=> 'dif_previous_a10', 'label'=> 'dif_previous_a10' ],
            ['prop'=> 'change_alert', 'label'=> 'change_alert' ],
            ['prop'=> 'num_not_change_alert', 'label'=> 'num_not_change_alert' ],
            ['prop'=> 'alert_decrease', 'label'=> 'alert_decrease' ],
            ['prop'=> 'alert_increase', 'label'=> 'alert_increase' ],
            ['prop'=> 'avg_recovered', 'label'=> 'avg_recovered' ],
            ['prop'=> 'error', 'label'=> 'error' ],
            ['prop'=> 'comment', 'label'=> 'comment' ]
        ];

        return ['result'=> $object,'columns'=> $columns];

    }

    public function getAuthUser()
    {
        return Auth::guard('api')->user();
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
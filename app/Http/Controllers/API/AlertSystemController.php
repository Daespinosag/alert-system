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
     * @param AlertRepository $alertRepository
     * @param StationTypeRepository $stationTypeRepository
     * @param ConnectionRepository $connectionRepository
     * @param FloodRepository $floodRepository
     * @param LandslideRepository $landslideRepository
     */
    public function __construct(
        StationRepository $stationRepository,
        NetRepository $netRepository,
        AlertRepository $alertRepository,
        StationTypeRepository $stationTypeRepository,
        ConnectionRepository $connectionRepository,
        FloodRepository $floodRepository,
        LandslideRepository $landslideRepository
    )
    {
        $this->stationRepository = $stationRepository;
        $this->netRepository = $netRepository;
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
     * @param Request $request
     * @return mixed
     */
    public function getStation(Request $request)
    {
        $stationId = $request->get('id');
        $alerts = $request->get('alerts');

        $response = ['station'=> null,'alerts'=>[]];
        $response['station'] = $this->stationRepository->getStation($stationId);

        $pivot =  $this->createDateFiveMinutal(Carbon::now());

        $final = (clone $pivot)->format('Y-m-d H:i:s');
        $pivotInitial = (clone $pivot)->addDay(-1);
        $initial = (clone $pivotInitial)->format('Y-m-d H:i:s');
        $dateRange = $this->generateDateRange($initial,$final);

        foreach ($alerts as $alert){
            $response['alerts'][$alert['code']] = $this->{'get'.ucwords(strtolower($alert['table'])).'Station'}($stationId,$initial,$final,$dateRange);
        }

        return $response;

       //$station = $this->stationRepository->getStation($id);

        //return $station;

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

    /**
     * @param int $stationId
     * @param string $initial
     * @param string $final
     * @param array $dateRange
     * @return array
     */
    protected function getLandslideStation(int $stationId, string $initial, string $final, array $dateRange) : array
    {
        $values = $this->landslideRepository->getBetweenData($stationId,$initial,$final);
        $values = $this->formatDateAlert($dateRange,$values);

        return $values;
    }

    /**
     * @param int $stationId
     * @param string $initial
     * @param string $final
     * @param array $dateRange
     * @return array
     */
    protected function getFloodStation(int $stationId, string $initial, string $final, array $dateRange) :array
    {
        $values = $this->floodRepository->getBetweenData($stationId,$initial,$final);
        $values = $this->formatDateAlert($dateRange,$values);

        return $values;
    }

    /**
     * @param array $dateRange
     * @param array $data
     * @return array
     */
    private function formatDateAlert(array $dateRange, array $data) : array
    {
        $dates = [];
        $values = [];

        foreach ($dateRange as $date)
        {
            $flag = true;
            $iterator = 0;
            while ($iterator < count($data) and $flag) {
                if ($data[$iterator]['date_execution'] == $date){
                    $flag = false;
                    array_push($dates,$date);
                    array_push($values,(float)$data[$iterator]['value']);
                }
                $iterator ++;
            }

            if ($flag){
                array_push($dates,$date);
                array_push($values,null);
            }
        }

        return ['dates' => $dates,'values' => $values];
    }

    /**
     * @param string $start
     * @param string $end
     * @return array
     */
    private function generateDateRange(string $start,string $end) :array
    {
        $range = [];

        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true ) $end = strtotime($end);

        if ($start > $end) return createDateRangeArray($end, $start);

        do {
            $range[] = date('Y-m-d H:i:s', $start);
            $start = strtotime("+ 5 minute", $start);
        } while($start <= $end);

        return $range;
    }

    private function createDateFiveMinutal(string $date)
    {
        $dateInitial = Carbon::parse($date);
        $residue = $dateInitial->minute % 5;

        $result = ($residue == 0) ? $dateInitial : $dateInitial->addSeconds( ((5 - $residue) * 60 ));
        $result->second = 0;

        return $result;
    }

}
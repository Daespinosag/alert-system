<?php

namespace App\Http\Controllers\AlertSystem;

use App\AlertSystem\Connection\DatabaseConfig;
use App\AlertSystem\Connection\SearchTableInExternalStaticConnection;
use App\AlertSystem\Extract\AcquisitionServerExtract;
use App\Events\AlertEchoCalculatedEvent;
use App\Repositories\AlertSystem\ControlNewDataRepository;
use App\Repositories\AlertSystem\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\LandslideRepository;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\AlertSystem\FloodRepository;
use App\AlertSystem\Alerts\LandslideAlert;
use App\AlertSystem\Alerts\FloodAlert;
use Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use Carbon\Carbon;
use App\Events\AlertFiveMinutesCalculated;

class testController extends Controller
{
    use SearchTableInExternalStaticConnection;
    /**
     * @var StationRepository
     */
    private $stationRepository;
    /**
     * @var ControlNewDataRepository
     */
    private $controlNewDataRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param StationRepository $stationRepository
     * @param ControlNewDataRepository $controlNewDataRepository
     * @param UserRepository $userRepository
     */

    public function __construct(
        StationRepository $stationRepository,
        ControlNewDataRepository $controlNewDataRepository,
        UserRepository $userRepository
    )
    {
        $this->stationRepository = $stationRepository;
        $this->controlNewDataRepository = $controlNewDataRepository;
        $this->userRepository = $userRepository;
    }

    /**
     *
     */
    public function testV2()
    {
        #$user = $this->userRepository->getCompleteUser(1)->toArray();
        #$user = $this->userRepository->getCompleteUser(1)->toArray();
        #dd($user);

        #$user = $this->recursive_change_key($user, ['pivot' => 'user_permissions']);
        #dd($user);

        //dd($this->stationRepository->getStationsAlerts('landslide',15,true));
        //dd($this->controlNewDataRepository->getUnsettledAlerts(2));
        //$connection = "";
        //$table      = "est_aranjuez";

        # 20/02/2017
        #  $dateTime   = Carbon::parse('2017-02-20 00:00:00');
        $dateTime   = Carbon::parse('2020-04-01 00:00:00');
        //$initialDateTime   = Carbon::p rarse('2019-08-13 08:55:00');
        //$finalDateTime   = Carbon::parse('2019-08-13 09:05:00');

        //$extract = new \App\AlertSystem\ControlAlert\ControlFloodAlert($dateTime);
        //$extract->execute();

        # Consultar aca todas las estaciones con su respectiva tabla para el sistema de alertas.

        for ($i = 0; $i <= 10;$i ++){

            $extract = new \App\AlertSystem\ControlAlert\ControlFloodAlert($dateTime);
            $extract->execute();

            $dateTime = $this->generateDateTime($dateTime,'+5 minutes');
        }

        dd('termine',$dateTime);
    }


    public function testConnectionsAndTablesServerAcquisition(){
        $tem = $this->stationRepository->getAllStationFlood();
        #$tem = $this->stationRepository->getAllStationLandslide();

        $arr = [];

        foreach ($tem as $station){
            $connection = $this->searchStaticConnection($station->connection_name,$station->station_table);

            $value = null;

            if ($connection){
                $value = DB::connection($connection)
                    ->table($station->station_table)
                    ->selectRaw("COUNT(fecha) as count")
                    ->where('fecha','=','2019-11-21')
                    ->first()->count;
            }

            $arr[] = [
                'station_id' => $station->station_sk,
                'connection'=>$station->connection_name,
                'table'=>$station->station_table,
                'data' => $value,
            ];
        }

        dd($arr);
    }
    /**
     * @param Carbon $dateTime
     * @param string $time
     * @return Carbon
     */
    public function generateDateTime(Carbon $dateTime,string $time) : Carbon {
        return date_add(clone ($dateTime), date_interval_create_from_date_string($time));
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //event(new AlertEchoCalculatedEvent(['alert'=>'a10']));
        //dd('stop');
        //dd( Auth::guard('api')->user());
        /*
        $possibleAlert = ['alert-a25','alert-a10'];

        $stations = $this->stationRepository->getStationsFromAlertsForMaps($possibleAlert);

        foreach ($stations as $station){
            $flag = $this->searchStaticConnection($station->net->connection->name,$station->table_db_name);
            dd($flag);
        }

        dd($stations);
        */
        /*$data = [];

        foreach ($possibleAlert as $alert){
            array_push($data,$this->stationRepository->getStationsForMaps($alert));
        }
        dd();
*/
#inicio a las 14:35:21

        $data = Carbon::parse('2018-11-04 12:45:00');
        $data2 = Carbon::parse('2018-11-04 13:00:00');

        $configurations1 = [
            'sendEmail'             => false,
            'sendEmailChanges'      => true,
            'insertDatabase'        => false,
            'sendEventData'         => false,
            'sendEventDataChanges'  => false,
            'initialDate'           => clone $data,//2017-11-07 23:55:00
            'finalDate'             => clone $data2,
            //'stations'              => [6,105]
        ];


        $alertSystem1 = new FloodAlert(
            new UserRepository(),
            new ConnectionRepository(),
            new StationRepository(),
            new FloodRepository(),
            new AlertRepository(),
            $configurations1
        );
        $alertSystem1->init();

        //dd($alertSystem1,'test controller');
/*
        $data = $alertSystem->getAlertsDefences();

        if ($data->changes){
            Mail::to('ideaalertas@gmail.com')
                ->bcc(['daespinosag@unal.edu.co','mayordan01@gmail.com'])
                ->send(new \App\Mail\TestEmail('Alerta por Inundación', $data));
        }
*/

        //dd($alertSystem->values);

        //return new \App\Mail\TestEmail('Alerta por Inundación', $data);

        $configurations = [
            'sendEmail'             => false,
            'sendEmailChanges'      => true,
            'insertDatabase'        => false,
            'sendEventData'         => false,
            'sendEventDataChanges'  => false,
            'initialDate'           => clone $data,//2017-11-07 23:55:00
            'finalDate'             => clone $data2,
            //'stations'              => [6,105]
        ];

        $alertSystem = new LandslideAlert(
            new UserRepository(),
            new ConnectionRepository(),
            new StationRepository(),
            new LandslideRepository(),
            new AlertRepository(),
            $configurations
        );
        $alertSystem->init();
        dd($alertSystem,$alertSystem1);

/*
        Mail::send('emails.testEmail',['alert' => 'enviando desde el sistema de alertas'], function ($message){
            $message->to('daespinosag@unal.edu.co','Alert System')->subject('test send emails');
        });
*/

        //Mail::to('jdzambranona@unal.edu.co')->send(new \App\Mail\TestEmail());
        //return new \App\Mail\TestEmail();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function recursive_change_key($arr, $set) {
        if (is_array($arr) && is_array($set)) {
            $newArr = array();
            foreach ($arr as $k => $v) {
                $key = array_key_exists( $k, $set) ? $set[$k] : $k;
                $newArr[$key] = is_array($v) ? $this->recursive_change_key($v, $set) : $v;
            }
            return $newArr;
        }
        return $arr;
    }
}

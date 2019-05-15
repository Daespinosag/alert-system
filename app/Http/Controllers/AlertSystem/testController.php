<?php

namespace App\Http\Controllers\AlertSystem;

use App\AlertSystem\Connection\SearchTableInExternalStaticConnection;
use App\Events\AlertEchoCalculatedEvent;
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
     * @param StationRepository $stationRepository
     */

    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
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
}

<?php

namespace App\Http\Controllers\AlertSystem;

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
use Mail;

class testController extends Controller
{
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
        $possibleAlert = ['alert-a25','alert-a10'];

        $stations = $this->stationRepository->getStationsFromAlertsForMaps($possibleAlert);
        dd($stations);
        /*$data = [];

        foreach ($possibleAlert as $alert){
            array_push($data,$this->stationRepository->getStationsForMaps($alert));
        }
        dd();
*/
        /*
        $alertSystem = new FloodAlert(
            new ConnectionRepository(),
            new StationRepository(),
            new FloodRepository(),
            new AlertRepository()
        );
*/
        //$alertSystem->init();
        //dd($alertSystem);
        /*
        $alertSystem = new LandslideAlert(
            new ConnectionRepository(),
            new StationRepository(),
            new LandslideRepository(),
            new AlertRepository()
        );
        $alertSystem->init();
        dd($alertSystem);
        */
        /*
        Mail::send('emails.contact',['alert' => 'enviando desde el sistema de alertas'], function ($message){
            $message->to('daespinosag@unal.edu.co','Alert System')->subject('test send emails');
        });*/
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

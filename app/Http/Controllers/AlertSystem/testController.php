<?php

namespace App\Http\Controllers\AlertSystem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\A25FiveMinutesRepository;
use App\Repositories\Administrator\AlertRepository;
use App\AlertSystem\Alerts\AlertA25;
use Event;
use Mail;


class testController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alertSystem = new AlertA25(
            new ConnectionRepository(),
            new StationRepository(),
            new A25FiveMinutesRepository(),
            new AlertRepository()
        );
        $alertSystem->init();
        dd($alertSystem);
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

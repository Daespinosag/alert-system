<?php

namespace App\Listeners;

use Mail;
use App\Events\AlertFiveMinutesCalculated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailsAlertFiveMinutes
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AlertFiveMinutesCalculated  $event
     * @return void
     */
    public function handle(AlertFiveMinutesCalculated $event)
    {
      Mail::send('emails.contact',['a25ForStations' => $event->valuesA25 ], function ($message){
          $message->to('daespinosag@unal.edu.co','Alert System')->subject('test send emails');
      });

    }
}

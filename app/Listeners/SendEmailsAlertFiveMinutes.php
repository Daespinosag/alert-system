<?php

namespace App\Listeners;

use App\Events\AlertEmailCalculatedEvent;
use Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailsAlertFiveMinutes
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(){/***/}

    /**
     * Handle the event.
     *
     * @param AlertEmailCalculatedEvent $event
     * @return void
     */
    public function handle(AlertEmailCalculatedEvent $event)
    {
      Mail::send('emails.contact',['a25ForStations' => $event->valuesA25 ], function ($message){
          $message->to('daespinosag@unal.edu.co','Alert System')->subject('test send emails');
      });

    }
}

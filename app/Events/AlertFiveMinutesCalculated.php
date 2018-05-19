<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AlertFiveMinutesCalculated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $valuesA25;

    /**
     * Create a new event instance.
     *
     * @param $valuesA25
     */
    public function __construct($valuesA25)
    {
        $this->valuesA25 = $valuesA25;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

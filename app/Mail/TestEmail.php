<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;


    protected $theme = 'email_style';

    private $title;

    private $data;
    private $messajeSubject;


    /**
     * Create a new message instance.
     *
     * @param $title
     * @param $data
     * @param $messageSubject
     */
    public function __construct($title, $data, $messageSubject)
    {
        $this->title = $title;
        $this->data = $data;
        $this->messajeSubject = $messageSubject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->messajeSubject)
            ->markdown('emails.testEmail')
            ->with(['title' => $this->title, 'data' => (object)$this->data]);
    }
}

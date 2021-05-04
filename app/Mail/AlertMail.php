<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     *
     */
    protected $theme = 'email_style';

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $messageSubject;

    /**
     * @var string
     */
    public $alert;

    /**
     * Create a new message instance.
     *
     * @param $title
     * @param $data
     * @param $messageSubject
     * @param string $alert
     */
    public function __construct($title = 'title', $data = [], $messageSubject = 'Subject',$alert = 'a25')
    {
        $this->title = $title;
        $this->data = $data;
        $this->messageSubject = $messageSubject;
        $this->alert = $alert;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->messageSubject)
            ->markdown('emails.alertEmail')
            ->with(['title' => $this->title, 'data' => (object)$this->data]);
    }
}

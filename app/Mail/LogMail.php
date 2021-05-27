<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogMail extends Mailable
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
     * Create a new message instance.
     *
     * @param $title
     * @param $data
     * @param $messageSubject
     */
    public function __construct($title = 'title', $data = [], $messageSubject = 'Subject')
    {
        $this->title = $title;
        $this->data = $data;
        $this->messageSubject = $messageSubject;
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
            ->markdown('emails.logEmail')
            ->with(['title' => $this->title, 'data' => (object)$this->data]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmationEmailUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $name;

    /**
     * Create a new message instance.
     *
     * @param string $code
     * @param string $name
     */
    public function __construct(string $code,string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Validación de correo - sistemas de alerta')
            ->markdown('emails.confirmationEmailUser')
            ->with(['code' => $this->code,'name' => $this->name]);
    }
}

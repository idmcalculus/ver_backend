<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = getenv('MAIL_FROM_ADDRESS');
        $name = getenv('MAIL_FROM_NAME');

        return $this->view('mail/'.$this->data['view'])
            ->with(['data' => $this->data])
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($this->data['subject']);
    }
}

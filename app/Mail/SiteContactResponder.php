<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SiteContactResponder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var array */
    public $data = [];

    /** @var string */
    public $subject = 'Thanks for your contact';


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
        return $this->markdown('emails.contact-auto-responder');
    }
}

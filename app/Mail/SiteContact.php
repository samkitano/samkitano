<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SiteContact extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var array */
    public $data = [];

    /** @var string */
    public $subject = 'Site Contact';


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
        return $this->markdown('emails.site-contact');
    }
}

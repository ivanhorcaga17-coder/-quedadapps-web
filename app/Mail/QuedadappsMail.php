<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuedadappsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $messageText;
    public $buttonUrl;
    public $buttonText;

    public function __construct($title, $messageText, $buttonUrl = null, $buttonText = null)
    {
        $this->title = $title;
        $this->messageText = $messageText;
        $this->buttonUrl = $buttonUrl;
        $this->buttonText = $buttonText;
    }

    public function build()
    {
        return $this->subject($this->title)
                    ->view('emails.quedadapps');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAccessLink extends Mailable
{
    use Queueable, SerializesModels;

    public $encryptedToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($encryptedToken)
    {
        $this->encryptedToken = $encryptedToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
  
        $encryptedUrl = url('/').'?accessToken='.$this->encryptedToken;

        return $this->from(env('APP_FROM_EMAIL'))
                    ->subject('KCB - SDG Tracker | Access Link')
                    ->markdown('emails.accessLink')
                    ->with([
                      'url'=>$encryptedUrl
                    ]);
    }
}

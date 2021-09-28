<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailDatabaseBackup extends Mailable
{
    use Queueable, SerializesModels;

    public $sqlFile;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sqlFile)
    {
        $this->sqlFile = $sqlFile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->subject(config('mail.backup.subject').now())
            ->markdown('emails.backup-database')
            ->attach($this->sqlFile);
    }
}

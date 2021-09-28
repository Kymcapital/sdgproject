<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Mail\EmailDatabaseBackup;
use Carbon;
use Storage;
use Mail;
use DB;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kcb:db-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup and email the database';

    protected $filename;

    protected $process;

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();

        //set filename with date and time of backup
        $this->filename = "backup-" . Carbon\Carbon::now()->format('Y-m-d') . ".sql";

        $this->process = new Process([
            'mysqldump',
            '--user=' . config('database.connections.mysql.username'),
            '--password=' . config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            '--result-file=' . storage_path('app') . "/" . $this->filename
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {
            $this->info('The backup has been proceed successfully.');
            $this->process->mustRun();

            //get mysqldump output file from local storage
            $getFile = storage_path('app/'.$this->filename);

            // Send email
            Mail::to(config('mail.backup.to'))
                ->cc(explode(',', config('mail.backup.cc')))
                ->send(new EmailDatabaseBackup($getFile));

            // delete local copy 
            unlink($getFile);

        } catch (ProcessFailedException $exception) {

            Mail::raw('There has been an error backing up the database.', function ($message) {
                $message->to("jackson.chegenye@oxygene.co.ke", "KCB SGD")->subject("Backup Error");
            });

            $this->error('The backup process has been failed - '. $exception);
            
        }

    }


}
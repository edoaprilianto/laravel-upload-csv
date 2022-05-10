<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CsvUpload;
use App\Events\ProgressEvent;



class UploadCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'the schedule uploaded';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){

        \Log::info("Cron is working fine!");

        $query = DB::select("
            select file_name, schedule_at
            from csv_uploads cu where schedule_at < now() and status = 'pending'
        ");

        if(count($query) > 0){
             event(new ProgressEvent('processing',$query[0]->file_name));
        }
    }
}

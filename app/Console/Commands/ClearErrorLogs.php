<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearErrorLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'errorlog:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Error Log file';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $log = storage_path('logs/laravel.log');

        if (file_exists($log)) {
            unlink($log);
            $this->info("Logs cleared.");
        } else {
            $this->info("No Log file to clear.");
        }
    }
}

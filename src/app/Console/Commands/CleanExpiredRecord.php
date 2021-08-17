<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\OriRecord;
use Carbon\Carbon;

class CleanExpiredRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ims:cleanExpiredRecord';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $timezone = config('services.time_zone');
        $keepDateTime = Carbon::now($timezone)->subDays(5)->format('Y-m-d 00:00:00');
        OriRecord::where('date_time', '<', $keepDateTime)->delete();
    }
}

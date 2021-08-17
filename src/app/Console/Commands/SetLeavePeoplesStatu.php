<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;

class SetLeavePeoplesStatu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ims:setStatu';

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
        $today = Carbon::now($timezone)->format('Y-m-d');
        $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
        $users = User::where('is_actived',true)->get();

        $users_records = $users->map(function ($user) use ($today,$now) {
            $record = $user->records()->where('date_time', 'like', '%' . $today . '%')->get()->last();

            if (!$record || $record->statu_id == 2){
                return;
            }

            $leave_time = $record->leave_at;
            $last_leave_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($leave_time)->format('Y-m-d H:i:s'));
            $range = $last_leave_time->diffInMinutes($now);

            if ($range >= 30){

                if ($record->leave_at == NULL){
                    $record->leave_at = $record->date_time;
                    $record->leave_timestamp = $record->start_timestamp;
                }

                $record->statu_id = 2;
                $record->save();
            }
        });
    }
}

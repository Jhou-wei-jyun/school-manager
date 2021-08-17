<?php

namespace App\Console\Commands;

use App\Area;
use App\Record;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CountNumberOfPeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Peoples:count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caculate Number of peoples in the same area';

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
        $today = Carbon::now($timezone);
        $lastTenMin = $today->addMinutes(-10)->format('Y-m-d H:i:s');
        $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
        $users = User::where('is_actived',true)->get();

        $userAreas = $users->filter(function ($user) use ($lastTenMin, $now) {
            return $user->record = $user->records()->whereBetween('records.leave_at', [$lastTenMin, $now])
                ->orderBy('records.date_time', 'asc')->get()->last();
        })->map(function ($user) {
            return $user->record;
        })->groupBy('area_id')->map(function ($num, $key) {
                $area = Area::find($key);
                $area->num_peoples = $num->count();
                $area->save();
            return $key;
        })->values();

        $areas_id = Area::all();

        //將無人區域改為零
        $areas_id->filter(function ($area) use ($userAreas) {
            return !$userAreas->contains($area->id) && $area->num_peoples > 0;
        })->map(function ($a) {
            $a->num_peoples = 0;
            $a->save();
        });
    }
}

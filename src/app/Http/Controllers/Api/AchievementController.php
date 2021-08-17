<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Achievement;

class AchievementController extends Controller
{
    // public function index(Request $request)
    // {
    //     //成就
    //     $timezone = config('services.time_zone');
    //     // $today = Carbon::now($timezone)->format('Y-m-d');
    //     $user_id = (int) $request->user_id;
    //     $date = (string) $request->date;
    //     $date = Carbon::parse($date)->setTimezone($timezone)->format('Y-m-d');
    //     $achievement = Achievement::where('user_id', $user_id)->whereDate('created_at', $date)->first();
    //     return $achievement;
    // }
    public function AchievementIndex(Request $request)
    {
        //成就
        $timezone = config('services.time_zone');
        // $today = Carbon::now($timezone)->format('Y-m-d');
        $user_id = (int) $request->user_id;
        $date = (string) $request->date;
        $date = Carbon::parse($date)->setTimezone($timezone);

        $achievement = Achievement::where('user_id', $user_id)->whereDate('created_at', $date->format('Y-m-d'))->first();
        if ($achievement == null) {
            $achievement_add = new Achievement([
                'user_id' => $user_id,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $achievement_add->save();
            $achievement = Achievement::where('user_id', $user_id)->whereDate('created_at', $date->format('Y-m-d'))->first();
        }
        return $achievement;
    }
}

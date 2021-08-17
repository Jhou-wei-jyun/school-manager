<?php

namespace App\API;

use App\Temperature;
use App\Record;
use App\Achievement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiAchievement
{
	public function AchievementUpdate($user_id, $Achievement_str)
    {
        //成就
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $achievement = Achievement::where('user_id', $user_id)->whereDate('created_at', $today);
        $achievementOBJ = $achievement->get();
        if ($achievementOBJ->isEmpty()) {
            $achievement_add = new Achievement([
                'user_id' => $user_id,
                $Achievement_str => true,
            ]);
            $achievement_add->save();
        } else {
            $achievement->update([
                $Achievement_str => true,
            ]);
        }
    }
}

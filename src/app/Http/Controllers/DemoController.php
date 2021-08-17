<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        $users = User::get();
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $users_last_appear_area = $users->filter(function ($user) use ($today) {
            $user->avatar = $user->avatar_url;
            $user->record = $user->records()->where('date_time', 'like', '%' . $today . '%')->get()->last();
            return $user->record && $user->record->statu_id != 2;
        });

        return $users_last_appear_area->values();
    }
}

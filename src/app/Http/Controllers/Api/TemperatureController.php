<?php

namespace App\Http\Controllers\Api;

use App\API\ApiHelper;
use App\Temperature;
use App\User;
use App\sm_relationship;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TemperatureController extends Controller
{
    use ApiHelper;

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return array
     */

    public function temper(Request $request)
    {
        $record_time = $request->add_time;
        $temperature_val = $request->temperature_val;
        $user_id = $request->recognition_id;
        // 機器代碼
        $mechanical_id = $request->mechanical_id;
        $equipment_verification_id = $request->equipment_verification_id;
        $recognition_name = $request->recognition_name;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $user_id = null;
        $school_id = null;

        if ($mechanical_id) {
            $school = sm_relationship::where('mechanical', '=', $mechanical_id)->first();
            $school_id = $school->school_id ? $school->school_id : null;
        }

        if ($recognition_name) {
            $user = User::where('name', 'like', '%' . $recognition_name . '%')->where('school_id','=',$school_id)->first();
            $user_id = $user->id ? $user->id : null;
        }

        if ($recognition_name) {
            $user = User::where('name', 'like', '%' . $recognition_name . '%')->where('school_id','=',$school_id)->first();
            $user_id = $user->id ? $user->id : null;
        }

        if ($user_id != null) {

            $recognition_name = $user->name;
            $existUserTemper = Temperature::where('user_id', '=', $user_id)
                ->where('record_time', 'like', '%' . $today . '%')->where('school_id','=',$school_id)->first();
            $temperature = $existUserTemper ? $existUserTemper : new Temperature;
        }

        $temperature->recognition_name = $recognition_name;
        $temperature->user_id = $user_id;
        $temperature->record_time = $record_time;
        $temperature->temperature_val = $temperature_val;
        $temperature->equipment_verification_id = $equipment_verification_id;
        $temperature->school_id = $school_id;
        $temperature->save();

        return $this->succeed('', 200);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $timestamp = $request->timestamp;

        if (!$user) {
            return $this->error('', 401);
        }

        if ($timestamp != 0) {
            $updated_at = date('Y-m-d H:i:s', $timestamp);
            $user_temper = Temperature::where('updated_at', '>', $updated_at)
                ->where('user_id', '=', $user->id)
                ->orderBy('updated_at', 'asc')
                ->get();
        } else {
            $user_temper = Temperature::where('user_id', '=', $user->id)->get();
        }

        $user_temper->count() == 0 ? $timestamp : $timestamp = strtotime($user_temper->last()['updated_at']);

        if ($user_temper->count() > 0) {
            $user_temper = $user_temper->map(function ($t) {
                $t->temperature_val = (float) $t->temperature_val;
                return $t;
            });
        }

        return $this->succeed(['timestamp' => (int) $timestamp, 'user_temper' => $user_temper], 200);
    }
}

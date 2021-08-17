<?php

namespace App\Http\Controllers;

use App\Jobs\FCMNotification;
use App\Admin;
use App\Notify;
use App\User;
use App\Parents;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function active_log($cause_id, $performed, $type, $result, $log)
    {
        activity()
            ->causedBy(Admin::find((int) $cause_id))
            ->performedOn($performed)
            ->withProperties([
                'type' => $type,
                'result' => $result
            ])
            ->log($log);
    }
    public function index(Request $request)
    {
        $timezone = config('services.time_zone');
        $range = Carbon::now($timezone)->subDays(7)->startOfDay();
        $notifies = Notify::where('school_id', $request->school_id)
            ->where('sent_type', 'App\Admin')
            ->where('created_at', '>=', $range)
            ->orderBy('id', 'desc')
            ->with('sent')
            ->with('users')
            ->with('parents')
            ->get();

        $notifies = $notifies->map(function ($notify) {
            $collection = collect([
                'id' => $notify->id,
                'title' => $notify->title,
                'message' => $notify->message,
                'image' => json_decode($notify->image, true),
                'target' => $notify->target,
                'sent' => $notify->sent->account,
                'time' => $notify->created_at,
                'count' => $notify->users->count() + $notify->parents->count()
            ]);
            return $collection;
        });
        return $notifies;
    }
    public function parentindex(Request $request)
    {
        $users = Parents::where('school_id', $request->id)
            ->get();

        $users = $users->map(function ($user) {
            return [
                'user_id' => $user->parent_id,
                'user_name' => $user->name,
                'user_token' => $user->device_token,
            ];
        });

        return $users;
    }
    public function teacherindex(Request $request)
    {
        $users = User::where('school_id', $request->id)
            ->where('position_id', '!=', 10)
            ->where('is_actived', 1)
            ->with('profile')
            ->get();

        $users = $users->map(function ($user) {
            return [
                'user_id' => $user->id,
                'user_name' => $user->profile->name,
                'user_token' => $user->device_token,
            ];
        });

        return $users;
    }

    // public function teacherpush(Request $request)
    // {
    //     $title = $request->title;
    //     $message = $request->message;
    //     $user = $request->user;
    //     $type = $request->type;

    //     $for = $user;

    //     $notify = new Notify;
    //     $notify->title = $title;
    //     $notify->message = $message;
    //     $statu = 10;

    //     //not work
    //     if ($for == "0") {
    //         $user = User::all();
    //         $ios_user_token = $user->filter(function ($u) {
    //             return $u->system == 'ios';
    //         })->map(function ($token) {
    //             return $token->device_token;
    //         })->values();

    //         $android_user_token = $user->filter(function ($u) {
    //             return $u->system == 'android';
    //         })->map(function ($token) {
    //             return $token->device_token;
    //         })->values();

    //         $type_message = 'normal';
    //         $type_sound = $type;
    //         if ($type != 'default') {
    //             $type_sound = 'spaceship_alarm.mp3';
    //             $type_message = 'emergency';
    //             $statu = 11;
    //         }
    //         $notify->statu_id = $statu;
    //         $notify->save();
    //         $notify->users()->attach($user);

    //         if ($ios_user_token->count() != 0) {

    //             $data = [
    //                 'title' => $title,
    //                 'message' => $message,
    //                 'type' => $type_message,
    //                 'token' => $ios_user_token,
    //                 'sound' => $type_sound,
    //                 'system' => 'ios',
    //             ];

    //             $job = (new FCMNotification($data));
    //             $this->dispatch($job);
    //         }

    //         if ($android_user_token->count() != 0) {

    //             $data = [
    //                 'title' => $title,
    //                 'message' => $message,
    //                 'type' => $type_message,
    //                 'token' => $android_user_token,
    //                 'sound' => $type_sound,
    //                 'system' => 'android',
    //             ];

    //             $job = (new FCMNotification($data));
    //             $this->dispatch($job);
    //         }

    //         $notify->statu_id = $statu;
    //         $notify->save();
    //         $notify->users()->attach($user);

    //         return 'succeed!';
    //     }

    //     if ($for != "0") {
    //         $user = User::find($user);
    //         $user_token = $user->device_token;

    //         $type_message = 'normal';
    //         $type_sound = $type;
    //         if ($type != 'default') {
    //             $type_sound = 'spaceship_alarm.mp3';
    //             $type_message = 'emergency';
    //             $statu = 11;
    //         }
    //         $notify->statu_id = $statu;
    //         $notify->save();
    //         $notify->users()->attach($user);

    //         $data = [
    //             'title' => $title,
    //             'message' => $message,
    //             'type' => $type_message,
    //             'token' => $user_token,
    //             'sound' => $type_sound,
    //             'system' => $user->system,
    //         ];

    //         $job = (new FCMNotification($data));
    //         $this->dispatch($job);

    //     }
    //     return 'succeed!';
    // }
    public function teacherpush(Request $request)
    {
        $admin_id = (int) $request->admin_id;
        $title = $request->title;
        $message = $request->message;
        $type = $request->type;

        $notify = new Notify;
        $notify->sent_id = $request->sent_id;
        $notify->sent_type = 'App\Admin';
        $notify->school_id = $request->school_id;
        $notify->title = $title;
        $notify->message = $message;
        $notify->target = '教師';
        $statu = 10;

        // $A_file = $request->A_file;
        // $B_file = $request->B_file;
        // $C_file = $request->C_file;
        // if ($A_file != null) {
        //     $A_file  = base64_encode(file_get_contents($A_file));
        // }
        // if ($B_file != null) {
        //     $B_file  = base64_encode(file_get_contents($B_file));
        // }
        // if ($C_file != null) {
        //     $C_file  = base64_encode(file_get_contents($C_file));
        // }
        // $imagelist = array();
        // $imagelist['A_file'] = $A_file;
        // $imagelist['B_file'] = $B_file;
        // $imagelist['C_file'] = $C_file;
        // $notify->image = json_encode($imagelist);

        $user = User::where('school_id', $request->school_id)
            ->where('position_id', '!=', 10)
            ->where('is_actived', 1)
            ->get();

        $token = $user->map(function ($token) {
            return $token->device_token;
        })->values();

        //DB to save
        $notify->statu_id = $statu;
        $notify->save();
        $notify->users()->attach($user, ['status' => 0]);
        //log
        if ($admin_id) {
            $this->active_log($admin_id, $notify, "store", "success", "發送老師推播");
        }
        $type_message = 'normal';
        $type_sound = $type;
        if ($type != 'default') {
            $type_sound = 'spaceship_alarm.mp3';
            $type_message = 'emergency';
            $statu = 11;
        }

        $data = [
            'id' => $notify->id,
            'title' => $title,
            'message' => $message,
            'type' => $type_message,
            'token' => $token,
            'sound' => $type_sound,
            // 'system' => 'ios',
        ];

        $job = (new FCMNotification($data))->onConnection('redis_high');
        $this->dispatch($job);

        return 'succeed!';
    }
    public function parentpush(Request $request)
    {
        $admin_id = (int) $request->admin_id;
        $title = $request->title;
        $message = $request->message;
        $type = $request->type;

        $notify = new Notify;
        $notify->sent_id = $request->sent_id;
        $notify->sent_type = 'App\Admin';
        $notify->school_id = $request->school_id;
        $notify->title = $title;
        $notify->message = $message;
        $notify->target = '家長';
        $statu = 10;

        // $A_file = $request->A_file;
        // $B_file = $request->B_file;
        // $C_file = $request->C_file;
        // if ($A_file != null) {
        //     $A_file  = base64_encode(file_get_contents($A_file));
        // }
        // if ($B_file != null) {
        //     $B_file  = base64_encode(file_get_contents($B_file));
        // }
        // if ($C_file != null) {
        //     $C_file  = base64_encode(file_get_contents($C_file));
        // }
        // $imagelist = array();
        // $imagelist['A_file'] = $A_file;
        // $imagelist['B_file'] = $B_file;
        // $imagelist['C_file'] = $C_file;
        // $notify->image = json_encode($imagelist);

        $user = Parents::where('school_id', $request->school_id)->get();

        $token = $user->map(function ($token) {
            return $token->device_token;
        })->values();

        $type_message = 'normal';
        $type_sound = $type;
        if ($type != 'default') {
            $type_sound = 'spaceship_alarm.mp3';
            $type_message = 'emergency';
            $statu = 11;
        }

        $notify->statu_id = $statu;
        $notify->save();
        $notify->parents()->attach($user, ['status' => 0]);
        //log
        if ($admin_id) {
            $this->active_log($admin_id, $notify, "store", "success", "發送家長推播");
        }
        $data = [
            'id' => $notify->id,
            'title' => $title,
            'message' => $message,
            'type' => $type_message,
            'token' => $token,
            'sound' => $type_sound,
            // 'system' => 'ios',
        ];

        $job = (new FCMNotification($data))->onConnection('redis_high');
        $this->dispatch($job);

        return 'succeed!';
    }
    public function allpush(Request $request)
    {
        $admin_id = (int) $request->admin_id;
        $title = $request->title;
        $message = $request->message;
        $type = $request->type;

        $notify = new Notify;
        $notify->sent_id = $request->sent_id;
        $notify->sent_type = 'App\Admin';
        $notify->school_id = $request->school_id;
        $notify->title = $title;
        $notify->message = $message;
        $notify->target = '所有人';
        $statu = 10;

        // $A_file = $request->A_file;
        // $B_file = $request->B_file;
        // $C_file = $request->C_file;
        // if ($A_file != null) {
        //     $A_file  = base64_encode(file_get_contents($A_file));
        // }
        // if ($B_file != null) {
        //     $B_file  = base64_encode(file_get_contents($B_file));
        // }
        // if ($C_file != null) {
        //     $C_file  = base64_encode(file_get_contents($C_file));
        // }
        // $imagelist = array();
        // $imagelist['A_file'] = $A_file;
        // $imagelist['B_file'] = $B_file;
        // $imagelist['C_file'] = $C_file;
        // $notify->image = json_encode($imagelist);

        //not work

        $user = User::where('school_id', $request->school_id)
            ->where('position_id', '!=', 10)
            ->where('is_actived', 1)
            ->get();
        $parent = Parents::where('school_id', $request->school_id)->get();

        $token_user = $user->map(function ($token) {
            return $token->device_token;
        })->values();

        $token_parent = $parent->map(function ($token) {
            return $token->device_token;
        })->values();

        $token = $token_user->merge($token_parent);


        $type_message = 'normal';
        $type_sound = $type;
        if ($type != 'default') {
            $type_sound = 'spaceship_alarm.mp3';
            $type_message = 'emergency';
            $statu = 11;
        }

        $notify->statu_id = $statu;
        $notify->save();
        $notify->users()->attach($user, ['status' => 0]);
        $notify->parents()->attach($parent, ['status' => 0]);
        //log
        if ($admin_id) {
            $this->active_log($admin_id, $notify, "store", "success", "發送所有人推播");
        }
        $data = [
            'id' => $notify->id,
            'title' => $title,
            'message' => $message,
            'type' => $type_message,
            'token' => $token,
            'sound' => $type_sound,
            // 'system' => 'ios',
        ];

        $job = (new FCMNotification($data))->onConnection('redis_high');
        $this->dispatch($job);


        return 'succeed!';
    }
}

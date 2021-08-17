<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Item;
use Illuminate\Support\Str;
use App\API\ApiHelper;

class ItemController extends Controller
{
     /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return array
     */


    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), User::$registerRule);

    //     if ($validator->fails()) {
    //         return $this->errors('fails', $validator->errors(), 400);
    //     }

    //     $userName = $request->name;
    //     $userMac = $request->mac;
    //     $userGender = $request->gender;
    //     $userPosition = $request->position_id;
    //     $userDevice = $request->device_token;
    //     $userAccount = $request->account;

    //     $user = new User;
    //     $user->account = $userAccount;
    //     $user->name = $userName;
    //     $user->mac = $userMac;
    //     $user->gender = $userGender;
    //     $user->position_id = $userPosition;
    //     $user->device_token = $userDevice;
    //     $user->api_token = Str::random(60);
    //     $user->save();

    //     return $this->succeed('register',200);
    // }

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), User::$loginRule);

    //     if ($validator->fails()) {
    //         return $this->error($validator->errors(), 400);
    //     }

    //     $account = $request->account;
    //     $macs = $request->macs;
    //     $device_token = $request->device_token;
    //     // $user = User::where([['account','=',$account],['mac','=',$mac]])->first();
    //     $user = User::where('account','=',$account)->first();
    //     $have = collect($macs)->filter(function($m) use($user){
    //         return $m == $user->mac;
    //     });

    //     if ($have->count() == 0){
    //         return $this->error('account or mac invlid',400);
    //     }

    //     $user->device_token = $device_token;
    //     $user->api_token = Str::random(60);
    //     $user->save();

    //     return $this->succeed([
    //         'user_id' => $user->id,
    //         'api_token' => $user->api_token,
    //     ], 200);
    // }
}

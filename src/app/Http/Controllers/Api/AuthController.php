<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\TryLoginlog;
use App\Loginlog;
use App\User;
use App\Parents;
use Illuminate\Support\Str;
use App\API\ApiHelper;
use App\By_pass;
use Exception;


class AuthController extends Controller
{
    use ApiHelper;

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return array
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), User::$registerRule);

        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 400);
        }

        $userName = $request->name;
        $userMac = $request->mac;
        $userGender = $request->gender;
        $userPosition = $request->position_id;
        $userDevice = $request->device_token;
        $userAccount = $request->account;

        $user = new User;
        $user->account = $userAccount;
        $user->name = $userName;
        $user->mac = $userMac;
        $user->gender = $userGender;
        $user->position_id = $userPosition;
        $user->device_token = $userDevice;
        $user->api_token = Str::random(60);
        $user->save();

        return $this->succeed('register', 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), User::$loginRule);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 400);
        }

        $account = $request->account;
        $macs = $request->macs;
        $device_token = $request->device_token;
        $password = $request->password;
        $system = $request->system;

        // $user = User::where([['account','=',$account],['mac','=',$mac]])->first();
        $user = User::where('account', '=', $account)->first();
        $have = collect($macs)->filter(function ($m) use ($user) {
            return $m == $user->mac;
        });

        if ($have->count() == 0 && $user->password != $password) {
            return $this->error('account or mac invlid', 400);
        }

        $user->device_token = $device_token;
        $user->system = $system;
        $user->api_token = Str::random(60);
        $user->save();

        return $this->succeed([
            'user_id' => $user->id,
            'api_token' => $user->api_token,
        ], 200);
    }

    public function SignOut(Request $request)
    {

        $now = Carbon::now()->toDateTimeString();
        $phone = $request->phone;

        $teacher = User::where('phone', $phone);
        $teacher->update(
            [
                'device_token' => null,
            ]
        );
        $parent = Parents::where('phone', $phone);
        $parent->update(
            [
                'device_token' => null,
            ]
        );
        $log = Loginlog::where('phone', $phone)->get();
        if (!$log->isEmpty()) {
            $log->last()->update(
                [
                    'signout_datetime' => $now,
                ]
            );
        }
        Auth::logout();
        return $this->succeed('', 200);
    }
    public function device_token_online_check(Request $request)
    {
        $device_token = $request->dt;
        try {
            $parent_check = Parents::where('device_token', $device_token)->whereNotNull('device_token')->get();
            $user_check = User::where('device_token', $device_token)->whereNotNull('device_token')->get();
            if ($parent_check->isEmpty() && $user_check->isEmpty()) { //無相同token，回傳false至App，重新電話認證
                return $this->succeed(false, 200);
            } else {
                return $this->succeed(true, 200);
            }
        } catch (Exception $e) {
            return $this->error($e, 400);
        }
    }
    public function beforeLogin_log(Request $request)
    {
        $phone = $request->phone; //string
        $brand = $request->brand; //string
        $osVersion = $request->osVersion; //string
        $deviceModel = $request->deviceModel; //string
        $ipaddress = $request->ipaddress;
        $datetime = $request->datetime; //datetime
        $now = Carbon::parse($datetime);
        $now_str = $now->toDateTimeString();
        $before_30_str = $now->addMinutes(-5)->toDateTimeString();

        //30分內登入超過5次，BAN(登入成功會清除)
        $trylog = TryLoginlog::where('ipaddress', $ipaddress)
            ->whereBetween('datetime', [$before_30_str, $now_str])
            ->get();
        if ($trylog->count() >= 5) {
            return $this->error('BAN', 400);
        }

        $log = new TryLoginlog;
        $log->phone = $phone;
        $log->brand = $brand;
        $log->osVersion = $osVersion;
        $log->deviceModel = $deviceModel;
        $log->ipaddress = $ipaddress;
        $log->datetime = $datetime;
        $log->save();

        return $this->succeed('', 200);
    }
    public function login_log(Request $request)
    {
        $phone = $request->phone; //string
        $brand = $request->brand; //string
        $osVersion = $request->osVersion; //string
        $deviceModel = $request->deviceModel; //string
        $uuid = $request->uuid;
        $datetime = $request->datetime; //datetime
        $ipaddress = $request->ipaddress; //string
        $app_version = $request->app_version;


        $log = new Loginlog;
        $log->phone = $phone;
        $log->brand = $brand;
        $log->osVersion = $osVersion;
        $log->deviceModel = $deviceModel;
        $log->uuid = $uuid;
        $log->ipaddress = $ipaddress;
        $log->app_version = $app_version;
        $log->login_datetime = $datetime;
        $log->save();
        //登入後清除tryLogIn_log防止BAN
        TryLoginlog::where(function ($query) use ($log) {
            $query->where('phone', $log->phone)
                ->orWhere('ipaddress', $log->ipaddress);
        })->delete();

        return $this->succeed('', 200);
    }
    public function getByPass(Request $request)
    {
        $by_pass = By_pass::all()->pluck('phone');

        return $this->succeed($by_pass, 200);
    }
}

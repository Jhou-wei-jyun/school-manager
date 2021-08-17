<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use Illuminate\Http\Request;

use App\School;
use App\Admin;
use App\User;
use DB;

class AccountController extends Controller
{
    use ApiHelper;

    public function info(Request $request)
    {
        $account = $request->account;
        $admin = Admin::where('account',$account)->with('group')->first();

        $collection =  collect ( [
            'id' => $admin->id,
            'account' => $admin->account,
            'name' => $admin->name,
            'group_name' => $admin->group->group_name,
        ]);

        return $collection;
    }
    public function updateinfo(Request $request)
    {
        $id = $request->id;
        $avatar = $request->avatar;
        if ($avatar) {
            $avatar  = base64_encode(file_get_contents($avatar));
        }
        // dd($user_avatar);

        User::where('id',$id)
        ->update([
            'avatar' => $avatar,
        ]);

        return $this->succeed('',200);
    }
    public function getuser(Request $request)
    {
        $users = User::where('school_id',$request->school_id)
        ->whereNotIn('position_id',[10,20])->where('is_actived',true)
        ->with('position:id,name')
        ->with('admin.group')
        ->with('profile')
        ->with('user_type')
        ->get();

        $users = $users->map(function ($user) {

            $collection =  collect ( [
                'id' => $user->id,
                'avatar' => 'avatar/small/' . $user->profile->avatar,
                'name' => $user->profile->name,
                'account' => $user->admin == null ? null : $user->admin->account,
                'gender' => $user->profile->gender,
                'phone' => $user->phone == null ? null : $user->phone,
                'position' => $user->position->name,
                'mac' => $user->user_type->mac == null ? null : $user->user_type->mac->mac,
                'group_name' => $user->admin == null ? null : $user->admin->group->group_name,
            ]);
            return $collection;
        });
        return $users;


    }
}

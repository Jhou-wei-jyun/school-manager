<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Events\Message;
use App\API\ApiHelper;
use Illuminate\Http\Request;
use App\User;
use App\Parents;
use App\Chat_message;
use Carbon\Carbon;
use App\Events\PrivateEvent;

class ChatController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        // $userlist = User::where('school_id',$request->school_id)
        //             ->where('position_id', '!=', 10)
        //             ->select('name','avatar','id')
        //             ->get();
        $parentlist = Parents::where('school_id', $request->school_id)
            ->select('name', 'parent_id')
            ->get();
        // $list = array_sort_recursive(array_collapse([$userlist, $parentlist]));

        return ['parent' => $parentlist];
    }
    public function messagesave(Request $request)
    {

        // $timezone = config('services.time_zone');
        // $today = Carbon::now($timezone)->format('Y-m-d');
        $newMessage = new Chat_message;
        // $newMessage->date = $today;
        $newMessage->from = $request->sender_id;
        $newMessage->to = $request->to;
        $newMessage->message = $request->message;
        $newMessage->status = $request->status;
        $newMessage->school_id = $request->school_id;
        $newMessage->parent_id = $request->parent_id;
        // $message->user_id = $request->user_id;
        // $message->save();

        broadcast(new Message($newMessage));

        return $this->succeed('', 200);
    }
    public function getmessage(Request $request)
    {
        // if ($request->user_id != null) {
        //     $message = Chat_message::where('sender',$request->sender)
        //                 ->where('sender_id',$request->sender_id)
        //                 ->where('user_id', $request->user_id)
        //                 // ->orderBy('created_at','desc')
        //                 ->select(['message','status','created_at'])
        //                 // ->take(10)
        //                 ->get();
        // }
        // return [$request->sender_id, $request->parent_id];
        $message = Chat_message::where('from', $request->sender_id)
            ->where('parent_id', $request->parent_id)
            // ->orderBy('created_at','desc')
            ->select(['message', 'status', 'created_at'])
            // ->take(10)
            ->get();

        return $message;
    }
    // public function private(Request $request) {

    //     if ($request->user_id != null) {
    //         $user = User::where('id',$request->user_id)
    //                     ->get();
    //     }
    //     if ($request->parent_id != null) {
    //         $user = Parents::where('parent_id',$request->parent_id)
    //                     ->get();
    //     }
    //     broadcast(new Message($user));
    //     // broadcast(new PrivateEvent());
    //     return 'private';
    // }
    public function testsend(Request $request)
    {

        // // $timezone = config('services.time_zone');
        // // $today = Carbon::now($timezone)->format('Y-m-d');
        // $message = new Chat_message;
        // // $message->date = $today;

        // $message->from = $request->sender_id;
        // $message->to = $request->to;
        // $message->message = $request->message;
        // $message->status = $request->status;
        // $message->school_id = $request->school_id;
        // $message->parent_id = $request->parent_id;
        // // $message->user_id = $request->user_id;
        // $message->save();
        $newMessage = [
            'user_id' => 253,
            'parent_id' => 67,
            'from' => 4,
            'message' => $request->message,
            'status' => 0,
        ];
        $newMessage = (object)$newMessage;

        // $user = Auth::user()->api_token;
        // broadcast(new Message($newMessage));
        broadcast(new Message($newMessage));
        return $this->succeed('', 200);
    }
}

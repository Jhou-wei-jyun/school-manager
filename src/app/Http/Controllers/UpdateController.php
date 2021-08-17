<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Admin;

use Carbon\Carbon;
use App\API\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    use ApiHelper;

    public function store(Request $request)
    {

        $avatar_file = $request->file('avatar_file');
        $admin_id = (int)$request->admin_id;
        $school_id = $request->school_id;
        $title = $request->title;
        $filename = $request->A_name;

        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $avatar_name = $title . "-" . $current . ".jpg";
        $avatar_path = $storageFile . 'announce/' . $avatar_name;
        Storage::disk($storage)->putFileAs($storageFile . 'announce', $avatar_file, $avatar_name);

        $item = new Announcement;
        $item->admin_id = $admin_id;
        $item->school_id = $school_id;
        $item->title = $title;
        $item->filename = $filename;
        $item->avatar = $avatar_path;
        $item->is_show = true;
        $item->save();
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($item)
                ->withProperties([
                    'type' => 'store',
                    'result' => 'success',
                ])
                ->log('新增廣告');
        }
        // if ($request->B != null) {
        //     $item = new Announcement;
        //     $item->admin_id = $request->admin_id;
        //     $item->school_id = $request->school_id;
        //     $item->title = $request->title;
        //     $item->filename = $request->B_name;
        //     $item->avatar = $request->B;
        //     $item->is_show = true;
        //     $item->save();
        // }
        // if ($request->C != null) {
        //     $item = new Announcement;
        //     $item->admin_id = $request->admin_id;
        //     $item->school_id = $request->school_id;
        //     $item->title = $request->title;
        //     $item->filename = $request->C_name;
        //     $item->avatar = $request->C;
        //     $item->is_show = true;
        //     $item->save();
        // }
        return $this->succeed('', 200);
    }
    public function index(Request $request)
    {
        $items = Announcement::where('school_id', $request->school_id)
            ->where('is_show', true)
            ->with('admin:id,name')
            ->get();

        $items = $items->map(function ($item) {

            $collection =  collect([
                'id' => $item->id,
                'title' => $item->title,
                'filename' => $item->filename,
                'name' => $item->admin->name,
                'school_id' => $item->school_id,
                'onboard_date' => $item->created_at->format('Y-m-d'),
            ]);

            return $collection;
        });


        return $items;
    }
    public function info(Request $request)
    {
        $items = Announcement::where('id', $request->id)
            ->get();
        $items = $items->map(function ($item) {

            $collection =  collect([
                'id' => $item->id,
                'title' => $item->title,
                'avatar' => $item->avatar,
                'onboard_date' => $item->created_at->format('Y-m-d'),
            ]);

            return $collection;
        });


        return $items;
    }
    public function delete(Request $request)
    {
        $admin_id = (int)$request->admin_id;
        $item = Announcement::where('school_id', $request->school_id)
            ->where('id', $request->id)
            ->first();

        $storage = config('services.storage');
        Storage::disk($storage)->delete('/' . $item->avatar);
        $item->delete();
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($item)
                ->withProperties([
                    'type' => 'delete',
                    'result' => 'success',
                ])
                ->log('刪除廣告');
        }


        return $this->succeed('', 200);;
    }
}

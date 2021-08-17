<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use App\Area;
use App\Category;
use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    use ApiHelper;

    public function areas(Request $request)
    {
        $areas = Area::all();

        return $areas;
    }

    public function categories(Request $request)
    {
        $categories = Category::all();

        return $categories;
    }

    public function index(Request $request)
    {
        if ($request->id == 0) {
            $items = Item::where('active', '=', true)->with('categories')->get();
        }

        if ($request->id > 0) {
            $area = Area::find($request->id);
            $items = $area->items()->where('active', '=', true)->with('categories')->get();
        }

        // if ($items->count() > 0) {
            $items = $items->map(function ($item) {
                $item->category = collect($item->categories)->map(function ($c) {
                    return $c->name;
                });

                $item->details = json_decode($item->details);
                if ($item->photo != null) {
                    $item->photo = $item->photo_url;
                }
                return $item;
            });
        // }

        return $items;
    }

    public function update(Request $request)
    {
        $item = Item::find($request->id);
        if (!$item) {
            return;
        }

        if ($request->has('imageFile')) {
            $storageFile = config('services.storage_file');
            $storage = config('services.storage');

            $photoFile = $request->file('imageFile');
            $photoName = $item->id . '_' . time();
            $photoPath = $storageFile . 'photo/' . $photoName;
            Storage::disk($storage)->putFileAs($storageFile . 'photo', $photoFile, $photoName, 'public');
            $url = Storage::disk($storage)->url($photoPath);
            Storage::disk($storage)->delete('/' . $item->photo);
            $item->photo = $photoPath;
        }
        // return json_encode($request->details);
        $item->name = $request->name;
        $item->mac = $request->mac;
        // $item->details = $request->details;
        $item->save();

        return $this->succeed('', 200);
    }

    public function updateDetails($id, Request $request) {
        $details = $request->all();
        $storage = config('services.storage');
        // \Log::info($id);
        // \Log::info(json_encode($details));

        $item = Item::find($id);
        $item->details = json_encode($details);
        $item->save();

        $this->succeed('',200);
    }

    public function uploadFile(Request $request)
    {
        if (!$request->file('file')){
            return 'file is required';
        }

        $storageFile = config('services.storage_file');
        $storage = config('services.storage');

        $photoFile = $request->file('file');
        $photoName = time();
        $photoPath = $storageFile . 'photo/' . $photoName;
        Storage::disk($storage)->putFileAs($storageFile . 'photo', $photoFile, $photoName, 'public');
        $url = Storage::disk($storage)->url($photoPath);
        return $url;
    }

    public function check(Request $request){

        $validator = Validator::make($request->all(), Item::$registerItemRule);

        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 400);
        }

        $this->succeed('',200);
    }
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), Item::$registerItemRule);

        // if ($validator->fails()) {
        //     return $this->errors('fails', $validator->errors(), 401);
        // }

        $item_name = $request->name;
        $item_mac = $request->mac;
        $item_area_id = $request->area_id;
        $item_category_id = $request->category_id;
        $item_details = $request->details;
        // $item_details = collect($item_details)->map(function ($detail) {
        //     $detail = collect($detail)->filter(function($d){
        //         return ['title' => $d[0]['detailUrl'],
        //         'url' => $d['detailUrl']];
        //     });
        //     // $detail = $detail->map(function ($d) {
        //     //     return ['title' => $d->detailTitle, 'url' => $d['detailUrl']];
        //     // });
        //     return $detail;
        // });
        // return $item_details;

        $item = new Item;
        $item->name = $item_name;
        $item->mac = $item_mac;
        $item->details = $item_details;
        $item->level_id = 1;

        $item->save();

        $item->areas()->attach($item_area_id);
        $item->categories()->attach($item_category_id);
        if ($request->has('imageFile')) {

            $avatarFile = $request->file('imageFile');
            $storageFile = config('services.storage_file');
            $storage = config('services.storage');

            $avatarName = $item->id . '_' . time();
            $avatarPath = $storageFile. 'photo/' . $avatarName;
            Storage::disk($storage)->putFileAs($storageFile .'photo', $avatarFile, $avatarName, 'public');
            $url = Storage::disk($storage)->url($avatarPath);

            $item->photo = $avatarPath;
            $item->save();
        }

        return $this->succeed('', 200);
    }

    public function delete(Request $request)
    {
        $item_id = $request->id;

        $item = Item::find($item_id);

        $item->active = false;
        $item->save();

        return $this->succeed('', 200);
    }
}

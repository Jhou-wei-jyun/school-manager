<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AreaController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        return Area::get();
    }

    public function update(Request $request)
    {
        $area = Area::find($request->id);
        if (!$area) {
            return;
        }

        $area->name = $request->name;
        $area->max_num_peoples = $request->max_num_peoples;
        $area->save();

        return $this->succeed('', 200);
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $max_num_peoples = $request->max_num_peoples;

        if ($request->has("id")) {
            $area = Area::find($request->id);
        } else {
            $area = new Area;
        }

        $area->name = $name;
        $area->max_num_peoples = $max_num_peoples;

        if ($request->hasFile('lottie')) {
            $area->lottie = $this->uploadFile($request->file('lottie'),$area->lottie);
        }
        if ($request->hasFile('location_photo_social_0')) {
            $area->location_photo_social_0 = $this->uploadFile($request->file('location_photo_social_0'),$area->location_photo_social_0);
        }
        if ($request->hasFile('location_photo_social_1')) {
            $area->location_photo_social_1 = $this->uploadFile($request->file('location_photo_social_1'),$area->location_photo_social_1);
        }
        if ($request->hasFile('location_photo_social_2')) {
            $area->location_photo_social_2 = $this->uploadFile($request->file('location_photo_social_2'),$area->location_photo_social_2);
        }
        if ($request->hasFile('location_emergency_exit')) {
            $area->location_emergency_exit = $this->uploadFile($request->file('location_emergency_exit'),$area->location_emergency_exit);
        }

        $area->save();

        return $this->succeed('', 200);
    }

    private function uploadFile($file, $existFile)
    {
        $storage = config('services.storage');
        if ($existFile) {
            Storage::disk($storage)->delete('/' . $existFile);
        }

        $storageFile = config('services.storage_file');
        $fileEx = $file->getClientOriginalExtension();

        $fileName = time() . '.' . $fileEx;
        $filePath = $storageFile . 'map/' . $fileName;
        Storage::disk($storage)->putFileAs($storageFile . 'map', $file, $fileName, 'public');

        return $filePath;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\API\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    use ApiHelper;

    public function business(Request $request)
    {
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $bg_map = Storage::disk($storage)->url($storageFile . 'map/bg_map_static.png');

        return $this->succeed([
            'location_photo' => $bg_map,
            'location_emergency_exit' => 'https://smartcubeims.s3-ap-northeast-1.amazonaws.com/shoesconn/map/emergency_exit.json',
            'privacy_page' => [],
            'temperature_def' => 37.5
        ], 200);
    }

}

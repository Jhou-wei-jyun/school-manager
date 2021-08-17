<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\User;
use App\Department;
use App\Device;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class DeviceController extends Controller
{
    use ApiHelper;

    public function network(Request $request)
    {
        //Log::info('樹莓派確認網路狀況');
        return $this->succeed('',200);
    }

    public function IPupdate(Request $request)
    {
        $data = collect($request);
        Device::where('mac',$data['scanner_mac'])
            ->update(['ip' => $data['scanner_ip']]);

        // Device::where('mac',$request->scanner_mac)
        // ->update(['ip' => $request->scanner_ip]);

        return $this->succeed('', 200);
    }

    public function raspberrypiFile(Request $request)
    {
        $device_mac = $request->device_mac;
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $device = Device::where('mac',$device_mac)->first();

        if (!$device){
            return $this->error('This device are not register yet',400);
        }

        $files = Storage::disk($storage)->allFiles($storageFile .'pi/');

        $files = collect($files)->map(function($file) use($storage){
            return Storage::disk($storage)->url($file);
        });

        //Log::info('樹莓派取得py檔案列表:['.$device_mac.']');

        return $this->succeed($files,200);
    }
}

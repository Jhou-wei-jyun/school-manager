<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\User;
use App\Device;
use App\Area;
use App\Http\Resources\Api\GetList;
use Illuminate\Support\Facades\Log;


class AreaController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('', 401);
        }

        $areas = $user->areas()->get();
        $areas = $areas->map(function($area){
            return [
                'id' => $area->id,
                'name' => $area->name,
                'start_at' => $area->start_at,
                'finish_at' => $area->finish_at,
            ];
        });

        return $this->succeed($areas,200);
    }

    public function list(Request $request)
    {
        $areas = new GetList([
            'query' => Area::query(),
            'timestamp' => $request->timestamp,
            'type' => 'areas',
        ]);

        return $this->succeed($areas,200);
    }

    public function device(Request $request)
    {
        if (!$request->device_mac){
            return $this->error('device mac is request',400);
        }

        $device_mac = $request->device_mac;

        $device = Device::where('mac','=',$device_mac)->first();

        if (!$device){
            return $this->error('device is not register yet!',400);
        }

        //Log::info('樹莓派取得區域及ssid:['.$device_mac.']');

        return $this->succeed([
            'area_id' => $device->area_id,
            'ssid' => $device->ssid,
            'password' => $device->password],200);
    }
}

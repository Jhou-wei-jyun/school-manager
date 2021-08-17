<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;

use App\Admin;
use App\Area;
use App\Device;
use App\Http\Controllers\Controller;
use App\Item;
use App\User;
use App\Mac;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        Redis::del('device');
        // ->join('areas', 'devices.area_id', 'areas.id')->select('devices.*', 'areas.name as area_name')->get()
        $devices = Device::where('school_id', $request->school_id)
            ->with('area:id,name')
            ->with('school:school_id,school_name')
            ->get();
        $now = Carbon::now();

        $devices = $devices->map(function ($device) use ($now) {
            $deviceIsAlive = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($device->updated_at)->format('Y-m-d H:i:s'));
            $range = $now->diffInMinutes($deviceIsAlive);
            if ($range <= 1) {
                $device->is_alive = true;
            } else {
                $device->is_alive = false;
            }
            $collection =  collect([
                'id' => $device->id,
                'name' => $device->name,
                'mac' => $device->mac,
                'ip' => $device->ip,
                'ssid' => $device->ssid,
                'password' => $device->password,
                'rssi' => $device->rssi_setting,
                'school_name' => $device->school->school_name,
                'area_id' => $device->area->id,
                'area_name' => $device->area->name,
                'is_alive' => $device->is_alive,
            ]);

            return $collection;
        });

        return $devices;
    }

    public function areas(Request $request)
    {
        return Area::where('school_id', $request->school_id)->get();
    }

    public function scanner(Request $request)
    {
        $devices = Redis::hgetall('device');
        $devices = collect($devices)->map(function ($v, $k) {
            return [
                'device_date' => $v,
                'device_mac' => $k,
            ];
        });
        return $devices->values();
    }

    public function store(Request $request)
    {
        $admin_id = (int) $request->admin_id;
        $scanner_mac = $request->mac;
        $scanner_name = $request->name;
        $scanner_area_id = $request->area;
        $scanner_ssid = $request->ssid;
        $scanner_password = $request->password;

        if (!$scanner_area_id || !$scanner_mac || !$scanner_name) {
            return $this->error('', 400);
        }

        $device = new Device;
        $device->name = $scanner_name;
        $device->mac = $scanner_mac;
        $device->area_id = $scanner_area_id;
        $device->ssid = $scanner_ssid;
        $device->password = $scanner_password;
        $device->school_id = $request->school_id;
        $device->save();

        $area = Area::find($scanner_area_id);
        $area->num_devices = $area->num_devices + 1;
        $area->save();
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($device)
                ->withProperties([
                    'type' => 'store',
                    'result' => 'success'
                ])
                ->log('新增裝置');
        }
        Redis::HDEL('device', $scanner_mac);

        // $id = (int) $device->id;
        return $this->succeed('succeed', 200);
    }

    public function delete(Request $request)
    {
        if (!$request->id) {
            return $this->error('error', 400);
        }
        $admin_id = (int) $request->admin_id;
        $device = Device::find($request->id);
        $area = Area::find($device->area_id);
        $device->delete();
        $area->decrement('num_devices', 1);
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($device)
                ->withProperties([
                    'type' => 'delete',
                    'result' => 'success'
                ])
                ->log('刪除裝置');
        }
        return $this->succeed('succeed', 200);
    }

    public function scannerRecords(Request $request)
    {
        $records = Redis::hgetall($request->mac);
        $device = Device::where('mac', $request->mac)->first();
        $records = collect($records)->map(function ($record) use ($device) {
            $a = json_decode($record);
            $a = collect($a)->map(function ($r) use ($device) {
                $cube = Mac::where('mac', $r->mac)->first();
                if (!$cube) {
                    $cube = Item::where('mac', $r->mac)->first();
                }
                return [
                    'name' => $cube->mac,
                    'rssi' => $r->rssi + $device->rssi_setting,
                ];
            })->filter(function ($w) {
                return $w['name'];
            });

            return $a;
        });
        return $records->first();
    }

    public function editDevice(Request $request)
    {
        // if ($device_mac != '') {
        //     $device_mac_arr = str_split($device_mac, 2);
        //     $device_mac = join(':', $device_mac_arr);
        // }
        $admin_id = (int) $request->admin_id;
        $device = Device::find($request->id);
        if ($device->area_id != $request->area) {
            //new Area
            Area::where('id', $request->area)
                ->increment('num_devices', 1);
            // $area->num_devices = $area->num_devices + 1;
            // $area->save();

            //old Area
            Area::where('id', $device->area_id)
                ->decrement('num_devices', 1);
        }
        $device->update([
            'name' => $request->name,
            'area_id' => (int) $request->area,
            'rssi_setting' => $request->rssi,
            'ssid' => $request->ssid,
            'password' => $request->password,
        ]);
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($device)
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success'
                ])
                ->log('編輯裝置');
        }
        // $device->area_id = (int) $request->area;
        // // $device->mac = $device_mac;
        // $device->rssi_setting = $request->rssi;
        // $device->ssid = $request->ssid;
        // $device->password = $request->password;
        // $device->name = $request->name;
        // $device->save();

        return $this->succeed('', 200);
    }

    public function uploadFile(Request $request)
    {
        $storage = config('services.storage');
        if (!$request->has('file')) {
            return '沒檔案';
        }
        $storageFile = config('services.storage_file');
        $file = $request->file('file');
        $validFile = $file->isValid();
        $PiName = $file->getClientOriginalName();
        $PiPath = $storageFile . 'pi/' . $PiName;
        Storage::disk($storage)->putFileAs($storageFile . 'pi', $file, $PiName, 'public');
        $url = Storage::disk($storage)->url($PiPath);
        return $url;
    }

    public function allFiles(Request $request)
    {
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $allFiles = Storage::disk($storage)->allFiles($storageFile . 'pi/');

        $allFiles = collect($allFiles)->map(function ($file) {
            $fileName = explode("/", $file);
            if (count($fileName) == 3) {
                return $fileName[2];
            } else {
                return $fileName[1];
            }
        });

        return $allFiles;
    }

    public function deleteFile(Request $request)
    {
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $fileName = $request->name;

        Storage::disk($storage)->delete($storageFile . 'pi/' . $fileName);

        return $this->succeed('', 200);
    }
}

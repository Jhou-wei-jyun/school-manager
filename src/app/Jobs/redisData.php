<?php

namespace App\Jobs;

use App\Device;
use App\Item;
use App\Record;
use App\User;
use App\Mac;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class redisData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data['records'];

        $scanner_mac = $this->data['scanner_mac'];
        $scanner_ip = $this->data['scanner_ip'];
        $scanner_ssid = $this->data['scanner_ssid'];
        $timezone = config('services.time_zone');
        // Log::info('1.scanner_mac:' . $scanner_mac);
        $taiwanToday = Carbon::now($timezone)->format('Y-m-d H:i:s');
        $devices = Device::where('mac', $scanner_mac)->first();
        if (!$devices) {
            // Log::info('2.無裝置'.$scanner_mac);
            Redis::hmset('device', [$scanner_mac => $taiwanToday]);
            return;
        }
        // Log::info('樹莓派上傳人員紀錄:[' . $scanner_mac . ']');
        if ($devices) {
            // Log::info('2.有裝置' . $scanner_mac);
            Redis::del($scanner_mac, 'to delete');
            Redis::hmset((String) $scanner_mac, [collect($data)->toJson()]);
            $devices->ip = $scanner_ip ? $scanner_ip : null;
            $devices->updated_at = Carbon::now();
            $devices->save();
        }
        /**0703 */
        $data = collect($data)->filter(function ($record) use ($devices) {
            $validator = Validator::make(collect($record)->all(), Record::$recordRule);
            $rssi = $record['rssi'] + $devices->rssi_setting;
            return !$validator->fails();
        });

        $macs = collect($data)->map(function ($record) {
            return (string) $record['mac'];
        });

        // $user_cubes = User::whereIn('mac', $macs)->get('mac')->map(function ($mac) {
        //     return $mac->mac;
        // });
        $user_cubes = Mac::whereIn('mac', $macs)->get('mac')->map(function ($mac) {
            return $mac->mac;
        });
        $item_cubes = Item::whereIn('mac', $macs)->get('mac')->map(function ($mac) {
            return $mac->mac;
        });

        $existMacs = collect([]);
        $existMacs = $existMacs->merge($item_cubes);
        $existMacs = $existMacs->merge($user_cubes);

        $users_record = collect($data)->filter(function ($record) use ($existMacs) {
            return $existMacs->contains($record['mac']);
        });

        $users_record->map(function ($record) {
            $record = json_decode(collect($record));
            $data = collect($record);
            Redis::hmset('cubes', [$record->mac . '_' . $record->area_id . '_' . $record->date_long => $data]);
        });

        $log_file_path = storage_path('redis.log');
        $log_info = ['begin_date' => date('Y-m-d H:i:s'),
                    'data'=> Redis::hgetall('cubes')];
        $log_info_json = json_encode($log_info) . "\r\n";
        File::append($log_file_path, $log_info_json);
    }
}

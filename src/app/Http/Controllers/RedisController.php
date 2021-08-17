<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Record;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Jobs\FCMNotification;
use Illuminate\Support\Facades\Storage;


class RedisController extends Controller
{
    public function index(Request $request)
    {
       return Redis::hgetall('cubes');
        return $users_id = collect(Redis::hgetall('cubes'))->keys()->map(function($mac){
            return [$mac => Redis::hgetall($mac)];
        });
        // $a = Storage::disk('s3')->allFiles('shoesconn/property_details');
        // $a = collect($a)->map(function($p){
        //     $p = Storage::disk('s3')->url($p);
        //     return $p;
        // });
        // return $a;
        // return Redis::hgetall('device');
        // $data = [
        //     'title' => '測試',
        //     'message' => '測試訊息',
        //     'type' => 'alert',
        //     'token' => 'eM6ipVhKRHafYbp6smoZRH:APA91bECtUkM69XuRK1kQ-Rxv_6UlzaewVvINevXhw95WoJd0JUMa5j35_5srEjI6a709G_2uqZ8TjP070VJbCzC5OjidjhSvjRCxcuTuh2-GKljPXvMR96BKWdLGqgYfayPuhCMhG6Z',
        //     'sound' => 'spaceship_alarm.mp3',
        // ];

        // $job = (new FCMNotification($data));
        // $this->dispatch($job);

        // return;
            $cubes = Redis::hgetall('cubes');
            $cubes = collect($cubes)->keys()->map(function ($cube_mac){
                $records = collect(Redis::hgetall($cube_mac))->sortKeys()->values();
            });
            return $cubes;
        //    $all = collect($users)->map(function($u_id){
        //         $list = Redis::hgetall($u_id);
        //         $user = User::find($u_id);
        //         $list = collect($list)->map(function($v,$k)use($user){
        //             $value = json_decode($v);
        //             return [
        //                 'name' => $user->name,
        //                 'date_time' => $k,
        //                 'dapartment' => Department::find($value->department_id)['name'],
        //                 'times' => $value->times,
        //                 'start_timestamp' => $value->start_timestamp,
        //                 'leave_timestamp' => $value->leave_timestamp
        //             ];
        //         });
        //         return $list->values();
        //     });
        //     return $all;

        /** 測試 */

        // Redis::del(11,'to delete');
        // Redis::del(14,'to delete');
        // Redis::del('users','to delete');
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $users_id = Redis::hgetall('users');
        $users_id = collect($users_id)->keys();
        $users = User::whereIn('id', $users_id)->with('areas')->get();
        // Redis::del('users','to delete');
        /* MARK - 新的record 未存DB*/
        $redisRecords = $users_id->map(function ($user_id) {
            $records = collect(Redis::hgetall($user_id))->sortKeys()->values();
            // Redis::del($user_id, 'to delete');
            $records = $records->map(function ($record) {
                return json_decode($record);
            });
            return [
                'user_id' => $user_id,
                'records' => $records,
            ];
        });

        if ($redisRecords->count() == 0) {
            return;
        }
        // echo 'redis:'.$redisRecords."\r\n";

        /* MARK -已存DB */
        $today = Carbon::now($timezone)->format('Y-m-d');
        $todayRecord = $users->filter(function ($user) use ($today) {
            $user->lastRecord = $user->records()->where('records.date_time', 'like', '%' . $today . '%')->get()->last();
            return $user->lastRecord;
        });

        /** 已有步數在DB user */
        $existRecordUsers = $todayRecord->map(function ($user) {
            return $user->id;
        });

        /** 新的沒有紀錄的 user */
        $newRecords = $redisRecords->filter(function ($item) use ($existRecordUsers) {
            return !$existRecordUsers->contains($item['user_id']);
        });

        if ($newRecords->count() > 0) {
            echo '今日無新records:' . $newRecords;
            $newFirstRecords = $newRecords->map(function ($item) {
                $item['records'] = $item['records']->first();
                return $item;
            });

            /* 今日第一筆紀錄*/
            $newFirstRecords = $newFirstRecords->map(function ($records) use ($timezone) {

                $record = $records['records'];
                $user = User::find($records['user_id']);
                $start_at = $user->department->start_at;
                $work_start_at = Carbon::now($timezone)->format('Y-m-d') . ' ' . $start_at . ':00';
                $work_start_at = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($work_start_at)->format('Y-m-d H:i:s'));
                $user_start_at = Carbon::now($timezone)->format('Y-m-d') . ' ' . $record->date . ':00';
                $user_start_at = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($record->date)->format('Y-m-d H:i:s'));
                $range = $user_start_at->diffInMinutes($work_start_at, false);

                if ($range > 0) {
                    $statu_id = 5;
                } else if ($range == 0) {
                    $statu_id = 3;
                } else {
                    $statu_id = 1;
                }

                $user_id = $records['user_id'];
                $rssi = $record->rssi;
                $area_id = $record->area_id;
                $date_time = $record->date;
                $leave_at = $record->date;
                $start_timestamp = $record->start_timestamp;
                $leave_timestamp = $record->start_timestamp;

                return [
                    'user_id' => $user_id,
                    'rssi' => $rssi,
                    'area_id' => $area_id,
                    'date_time' => $date_time,
                    'statu_id' => $statu_id,
                    'start_timestamp' => $start_timestamp,
                    'leave_at' => $leave_at,
                    'leave_timestamp' => $leave_timestamp,
                ];
            });
            /** 存DB */
            // echo '要存DB的data:'.$newFirstRecords."\r\n";
            // Record::insert($newFirstRecords->toArray());

            $users_id = $newFirstRecords->map(function ($record) {
                return $record['user_id'];
            });

            /*刪掉redis中已存進DB的第一筆*/
            $redisRecords = $redisRecords->map(function ($item) use ($users_id) {
                $ee = $users_id->map(function ($id) use ($item) {

                    if ($id == $item['user_id']) {
                        $chunk = $item['records']->splice(1);
                        $chunk->all();
                        $item['records'] = $chunk->all();
                    }

                    return $item;

                });
                return $ee;
            });
        }

        // echo '目前redis records:'.$redisRecords;

        $newRecordUsers = $redisRecords->map(function ($record) {
            return $record['user_id'];
        });

        // echo 'users_id:'.$newRecordUsers;

        // $newRecordUsers = collect($newRecordUsers)->collapse()->unique()->values();

        // echo '有新紀錄的user:'.$newRecordUsers;

        $todayRecordsUsers = User::whereIn('id', $newRecordUsers)->get();

        $todayRecords = $todayRecordsUsers->map(function ($user) use ($today) {
            return $user->records()->where('date_time', 'like', '%' . $today . '%')->get()->last();
        });

        /**  *** rssi > -60 & times > 0  *** */
        $newRecords = $redisRecords->filter(function ($newRecord) {
            $newRecord = collect($newRecord)['records']->filter(function ($record) use($newRecord) {
                $record->user_id = $newRecord['user_id'];
                if($record->rssi > -100 && $record->times > 0)
                return true;
            });
            return $newRecord;
        })->flatten();
        $newRecords = $todayRecords->map(function($record)use ($newRecords){
            for ($i = 0; $i <= count($newRecords) ; $i ++){
                echo 'newRecord:'.$newRecords[$i]['rssi'];
                // if ($record->user_id = $newRecords[$i]->user_id){
                //     echo 'same';
                // }
            }
        //    $newRecords = collect($newRecords)->map(function($newRecord) use ($record){
        //     if ($newRecord->user_id == $record->user_id){
        //         echo 'same';
        //         }
        //    });
            return;
        });
        // echo 'new:' . $newRecords;
        // for ($i = 0; $i < count($newRecords->first()); $i++) {
        //     $newRecords = $newRecords[$i]->records->fliter(function($newReocrd){
        //         return $newRecord->rssi > -60;
        //     });
        // }
        return;

        //  Record::insert($newFirstRecords->toArray());
        //   $users = User::whereIn('id',$users)->get();

        collect($users)->map(function ($user_id) use ($today, $timezone) {

            $records = collect(Redis::hgetall($user_id))->sortKeys()->values();
            Redis::del($user_id, 'to delete');
            $user = User::find($user_id);
            if (!$records) {
                Log::info('此user沒有暫存:' . $user->name);
                return;
            }

            $user_departments = $user->departments()->get();
            $user_departments = $user_departments->map(function ($id) {
                return $id->id;
            });

            $records->map(function ($record) use ($user, $today, $user_departments, $timezone) {

                $record = json_decode($record);
                $todayRecord = Record::where([['user_id', '=', $user->id], ['date_time', 'like', '%' . $today . '%']])->get()->last();

                if (!$todayRecord) {
                    $newRecord = new Record;

                    $department = Department::find($record->department_id);
                    $department_start_at = Carbon::now($timezone)->format('Y-m-d') . ' ' . $department->start_at . ':00';
                    $department_start_at = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($department_start_at)->format('Y-m-d H:i:s'));
                    $user_start_at = Carbon::now($timezone)->format('Y-m-d') . ' ' . $record->date . ':00';
                    $user_start_at = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($record->date)->format('Y-m-d H:i:s'));
                    $range = $user_start_at->diffInMinutes($department_start_at, false);

                    Log::info('判斷時間:' . $range);

                    if ($range > 0) {
                        $newRecord->statu_id = 5;
                        Log::info('statu 早到:' . $range);
                    } else if ($range == 0) {
                        $newRecord->statu_id = 3;
                        Log::info('statu 準時:' . $range);
                    } else {
                        $newRecord->statu_id = 1;
                        Log::info('statu 遲到:' . $range);
                    }

                    $newRecord->user_id = $user->id;
                    $newRecord->rssi = $record->rssi;
                    $newRecord->department_id = $record->department_id;
                    $newRecord->date_time = $record->date;
                    $newRecord->start_timestamp = $record->start_timestamp;
                    $newRecord->save();
                    Log::info('儲存後:' . $newRecord->statu_id);

                } else {

                    $department_id = $todayRecord->department_id;
                    $newDepartment_id = $record->department_id;
                    if ($department_id == $record->department_id) {
                        $todayRecord->leave_at = $record->end_date;
                        $todayRecord->leave_timestamp = $record->leave_timestamp;
                        $todayRecord->save();

                    } else if ($record->rssi >= -100 && $record->times >= 5) {
                        $start = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($todayRecord->date_time)->format('Y-m-d H:i:s'));
                        $leave = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($record->end_date)->format('Y-m-d H:i:s'));

                        $range = $leave->diffInMinutes($start);

                        if ($range <= 1) {
                            Log::info('進出時間太近判斷為經過');
                            Log::info('刪掉這一筆:' . $todayRecord->id);
                            $todayRecord->delete();
                            /**再撈上一筆 判斷相同部門就存離開時間*/
                            $todayRecord = Record::where([['user_id', '=', $user->id], ['date_time', 'like', '%' . $today . '%']])->get()->last();

                            /** 加log觀察 */
                            $log_file_path = storage_path('Records_log.log');
                            $log_info = ['date' => date('Y-m-d H:i:s'), 'record' => $todayRecord];
                            $log_info_json = json_encode($log_info) . "\r\n";
                            File::append($log_file_path, $log_info_json);

                            if ($todayRecord['department_id'] == $newDepartment_id) {
                                $todayRecord->leave_at = $record->end_date;
                                $todayRecord->leave_timestamp = $record->leave_timestamp;
                                $todayRecord->save();
                                return;
                            }

                        } else if ($range > 1 && $range <= 5) {

                            // Log::info('不同部門存新的離開');
                            $todayRecord->leave_at = $record->end_date;
                            $todayRecord->leave_timestamp = $record->leave_timestamp;
                            $todayRecord->save();
                        }
                        //   Log::info('不同部門存新的進入');
                        $newRecord = new Record;

                        $user_departments = $user_departments->filter(function ($item) use ($record) {
                            return $record->department_id == $item;
                        });
                        //   Log::info('使用者部門:'.$user_departments->count());

                        if ($user_departments->count() == 0) {
                            // Log::info('使用者到禁區');
                            $newRecord->statu_id = 6;
                        }

                        //   $lastScanTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($todayRecord->leave_at)->format('Y-m-d H:i:s'));
                        //   $newScanTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($record->date)->format('Y-m-d H:i:s'));

                        //   $scanRange = $newScanTime->diffInMinutes($lastScanTime);

                        $newRecord->user_id = $user->id;
                        $newRecord->rssi = $record->rssi;
                        $newRecord->department_id = $record->department_id;
                        $newRecord->date_time = $record->end_date;
                        $newRecord->start_timestamp = $record->leave_timestamp;
                        $newRecord->save();

                        //   if ($scanRange > 5){
                        //      /* 測試樹莓派未掃到此使用者達5分鐘以上 */
                        //      Log::info('測試樹莓派未掃到此使用者達5分鐘以上:'.$user->id);
                        //      $newRecord = new Record;
                        //      $newRecord->user_id = $user->id;
                        //      $newRecord->rssi = $record->rssi;
                        //      $newRecord->department_id = $record->department_id;
                        //      $newRecord->date_time = $todayRecord->leave_at;
                        //      $newRecord->leave_at = $record->date;
                        //      $newRecord->statu = 7;
                        //      $newRecord->save();
                        //   }
                    }
                }
            });
            Redis::del('users', 'to delete');
            //   Redis::del($user->id,'to delete');
            //   Log::info('刪掉這個key:'.$user->id);
            //   Redis::hgetall($user->id);
        });
        //   Redis::del('users','to delete');
    }
}

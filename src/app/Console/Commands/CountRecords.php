<?php

namespace App\Console\Commands;

use App\Record;
use App\OriRecord;
use App\User;
use App\Mac;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CountRecords extends Command
{
 /**
  * The name and signature of the console command.
  *
  * @var string
  */
 protected $signature = 'Records:count';

 /**
  * The console command description.
  *
  * @var string
  */
 protected $description = 'save records to database';

 /**
  * Create a new command instance.
  *
  * @return void
  */
 public function __construct()
 {
  parent::__construct();
 }

 /**
  * Execute the console command.
  *
  * @return mixed
  */
 public function handle()
 {
  $timezone = config('services.time_zone');
  $newRecords = collect(Redis::hgetall('cubes'));
  $temporaryKeys = $newRecords->keys();
  // Log::info('需要刪掉的暫存:'.$temporaryKeys);

  $newRecords = $this->getNewRecords($timezone, $newRecords);
  $newRecords = $this->getTotalScore($newRecords);
  $macs = $newRecords->keys();
  $existRecords = $this->getExistRecords($macs, $timezone);

    // 紀錄原始資料
    $records = collect(Redis::hgetall('cubes'))->map(function ($record) {
        $record = json_decode($record);
        return $record;
    });

    $date_time_now = Carbon::now($timezone)->format('Y-m-d H:i:s');
    $tag = strtotime($date_time_now);
    $ori_records = collect($records)->values()->map(function($record) use($tag){
        $record = json_decode(collect($record));
        return [
            'mac' => $record->mac,
            'area_id' => $record->area_id,
            'bat' => $record->bat,
            'rssi' => $record->rssi,
            'date_time' => $record->date_time,
            'date_long' => $record->date_long,
            'tag' => $tag
        ];
    });

    OriRecord::insert($ori_records->toArray());
    // 紀錄原始資料
  $dataResult = collect([]);
try {
  $existRecords->map(function ($existRecord) use ($newRecords, $dataResult) {
   $newRecords->map(function ($newRecord, $macAdress) use ($existRecord, $dataResult) {
    if (!$newRecord['detail']) {
     return;
    }

    $newLastRecord = $newRecord['detail']->count() > 1 ?
    $newRecord['detail']->last() :
    $newRecord['detail']->first();
    $lastRecord = $existRecord->user_type->user->records->last();

    if ($existRecord) {
     $lastRecordLeaveTime = !$lastRecord ?
     Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($newLastRecord->date_time)->format('Y-m-d H:i:s')) :
     Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($lastRecord->leave_at)->format('Y-m-d H:i:s'));

     $newRecordTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($newLastRecord->date_time)->format('Y-m-d H:i:s'));
     $rangeOfTheTime = $lastRecordLeaveTime->diffInMinutes($newRecordTime, false);
    }

    /* 在資料庫中記錄小於等於1筆 && 新的資料與舊的資料掃到時間相差小於等於3分鐘 */
    if ($existRecord->mac == $macAdress && $existRecord->user_type->user->records->count() <= 1 && $rangeOfTheTime <= 3) {
     $this->pushInsertData($existRecord, $newLastRecord, null, null, $dataResult);
     return;
    }

    /* 在資料庫中記錄小於等於1筆 && 新的資料與舊的資料掃到時間相差大於3分鐘 */
    if ($existRecord->mac == $macAdress && $existRecord->user_type->user->records->count() <= 1 && $rangeOfTheTime > 3) {

     /* 資料庫中有無資料驗證 */
     $existRecord->user_type->user->records->count() == 1 ?
     $this->pushInsertData($existRecord, $newLastRecord, $lastRecord->id, null, $dataResult) :
     $this->pushInsertData($existRecord, $newLastRecord, null, null, $dataResult);
     return;
    }

    /* 在資料庫中記錄大於1筆 && 新的資料與舊的資料掃到時間相差小於等於3分鐘 */
    if ($existRecord->mac == $macAdress && $existRecord->user_type->user->records->count() > 1 && $rangeOfTheTime <= 3) {

     /* 相同區域則只修改離開時間 */
     $lastRecord->area_id == $newLastRecord->area_id ?
     $this->pushInsertData($existRecord, $newLastRecord, $lastRecord->id, 'sameArea', $dataResult) :
     $this->pushInsertData($existRecord, $newLastRecord, null, null, $dataResult);
     return;

     /* 在資料庫中記錄大於1筆 && 新的資料與舊的資料掃到時間相差大於3分鐘,表示此cube消失了一陣子,因此新增一筆消失的時間紀錄 */
    } else if ($existRecord->mac == $macAdress && $existRecord->user_type->user->records->count() > 1 && $rangeOfTheTime > 3) {
     $this->pushInsertData($existRecord, $newLastRecord, $lastRecord->id, null, $dataResult);

     $dataResult->push([
      'user_id' => $existRecord->user_type->user->id,
      'rssi' => $lastRecord->rssi,
      'battery' => 100,
      'area_id' => $lastRecord->area_id,
      'date_time' => $lastRecord->leave_at,
      'start_timestamp' => $lastRecord->leave_timestamp,
      'leave_at' => $newLastRecord->date_time,
      'leave_timestamp' => $newLastRecord->date_long,
      'statu_id' => 7,
     ]);
     return;
    }
   });
  });

  $this->insertRecordData($dataResult);
  //$this->removeTemporaryData($temporaryKeys);
    Redis::del('cubes');
    } catch (Exception $e) {
    Log::error('caculate record error:' . $e);
    }
}

 private function getTotalScore($records)
 {
  $records = $records->map(function ($record) {
   $record = $record->map(function ($record_detail) {
    $record_detail = collect($record_detail)->map(function ($detail, $key) {
     $times = count($detail);
     $times_score = $times / 20 * 100;
     $rssi_average = (collect($detail)->sum('rssi')) / $times;
     $rssi_score = ($rssi_average + 80) * 2.5;
     $total_score = ($times_score + $rssi_score) / 2;
     return [
      'area_name' => $key,
      'times' => $times,
      'total_score' => ceil($total_score),
      'detail' => $detail,
     ];
    });
    return $record_detail->values()->first();
   });
   return $record;
  });

  $newRecords = $this->getHighestScore($records);
  return $newRecords;
 }

 private function getHighestScore($records)
 {
  $records = $records->map(function ($record) {
   $record = $record->reduce(function ($scoreA, $scoreB) {
    return $scoreA['total_score'] >= $scoreB['total_score'] ? $scoreA : $scoreB;
   });
   return $record;
  });

  return $records;
 }

 private function pushInsertData($existRecord, $newLastRecord, $id, $type, $dataResult)
 {
  if (!$id) {
   $data = [
    'user_id' => $existRecord->user_type->user->id,
    'rssi' => $newLastRecord->rssi,
    'area_id' => $newLastRecord->area_id,
    'date_time' => $newLastRecord->date_time,
    'start_timestamp' => $newLastRecord->date_long,
    'leave_at' => $newLastRecord->date_time,
    'leave_timestamp' => $newLastRecord->date_long,
    'battery' => 100, /* 暫時改為100% 'battery' => $newLastRecord->bat,*/
   ];
  } else {

   if ($type == 'sameArea') {
    $data = [
     'id' => $id,
     'user_id' => $existRecord->user_type->user->id,
     'rssi' => $newLastRecord->rssi,
     'leave_at' => $newLastRecord->date_time,
     'leave_timestamp' => $newLastRecord->date_long,
     'battery' => 100, /* 暫時改為100% 'battery' => $newLastRecord->bat,*/
    ];

   } else {
    $data = [
     'id' => $id,
     'user_id' => $existRecord->user_type->user->id,
     'rssi' => $newLastRecord->rssi,
     'area_id' => $newLastRecord->area_id,
     'date_time' => $newLastRecord->date_time,
     'start_timestamp' => $newLastRecord->date_long,
     'leave_at' => $newLastRecord->date_time,
     'leave_timestamp' => $newLastRecord->date_long,
     'battery' => 100, /* 暫時改為100% 'battery' => $newLastRecord->bat,*/
    ];
   }
  }

  $dataResult->push($data);
  return;
 }

 private function insertRecordData($dataResult)
 {
  $dataResult->map(function ($result) {
   $result = collect($result);
   $result->has('id') ? $record = Record::find($result['id']) : $record = new Record;
   if ($result->has('user_id')) {
    $record->user_id = $result['user_id'];
   }

   $record->rssi = $result['rssi'];
   $record->battery = $result['battery'];
   $record->leave_at = $result['leave_at'];
   $record->leave_timestamp = $result['leave_timestamp'];
   if ($result->has('area_id')) {
    $record->area_id = $result['area_id'];
   }

   if ($result->has('date_time')) {
    $record->date_time = $result['date_time'];
   }

   if ($result->has('statu_id')) {
    $record->statu_id = $result['statu_id'];
   }

   if ($result->has('start_timestamp')) {
    $record->start_timestamp = $result['start_timestamp'];
   }

   $record->save();
  });

  return;
 }

 private function getNewRecords($timezone, $newRecords)
 {
  $newRecords = $newRecords->map(function ($record) {
   $record = json_decode($record);
   return $record;
  });

  $dateTimeNow = Carbon::now($timezone)->format('Y-m-d H:i:s');
  $tag = strtotime($dateTimeNow);

  $newRecords = $newRecords->values()->groupBy('mac')->map(function ($record) {
   $record = $record->groupBy('area_id');
   $record = $record->map(function ($detail, $key) {
    return ['area_' . $key => $detail];
   });
   return $record->values();
  });

  return $newRecords;
 }

 private function getExistRecords($macs, $timezone)
 {
    $today = Carbon::now($timezone)->format('Y-m-d');
    $existRecords = Mac::whereIn('mac', $macs)->with(['user_type.user.records' => function ($recordQuery) use ($today) {
        $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('records.id', 'asc');
    }])->get();
    // $existRecords = $existRecords->map(function ($existRecord) {

    //     $collection =  collect ( [
    //         'id' => $existRecord->user_type->user->id,
    //         'records' => $existRecord->user_type->user->records,
    //     ]);

    //     return $collection;
    // });
//     $users = User::whereIn('mac', $macs)->get()->user_type;
//   $existRecords = $users::whereIn('mac', $macs)->with(['records' => function ($recordQuery) use ($today) {
//    $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('records.id', 'asc');
//   }])->get();

    if ($existRecords->count() != 0) {
        $existRecords = $existRecords->map(function ($existRecord) {
            return $existRecord;
        });
    }

    return $existRecords;
 }

 private function removeTemporaryData($temporaryKeys)
 {
  // Log::info('未刪除前:'.collect(Redis::hgetall('cubes'))->keys());
  $temporaryKeys->map(function ($key) {
   Redis::del($key);
  });
  // Log::info('已刪除:'.collect(Redis::hgetall('cubes'))->keys());
  return;
 }
}

<?php

namespace App\Http\Controllers\Api;

use App\API\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Record as RecordResource;
use App\Record;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use App\Device;
use App\Jobs\redisData;

class RecordController extends Controller
{
    use Apihelper;

    public function store(Request $request)
    {

        $data = collect($request);
        $job = (new redisData($data))->onConnection('redis_low');
        $this->dispatch($job);
        return $this->succeed('', 200);
    }

    public function show(Request $request, $timestamp)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('', 401);
        }

        $records = new RecordResource([
            'user' => $user,
            'timestamp' => $timestamp
        ]);

        collect($records)->count() == 0 ? $timestamp : $timestamp = strtotime(collect($records)->last()['updated_at']);

        return $this->succeed([
            'timestamp' => (int)$timestamp,
            'records' => $records
        ], 200);
    }
}

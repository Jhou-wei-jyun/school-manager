<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Statu;
use App\API\ApiHelper;
use App\Http\Resources\Api\GetList;

class StatuController extends Controller
{
     use ApiHelper;

    public function index(Request $request)
    {
        $status = new GetList([
            'query'=> Statu::query(),
            'timestamp'=> $request->timestamp,
            'type' => 'status',
        ]);

        return $this->succeed($status,200);
    }
}

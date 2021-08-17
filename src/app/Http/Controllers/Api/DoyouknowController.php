<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DoYouKnow;
use App\API\ApiHelper;
use App\Http\Resources\Api\GetList;

class DoyouknowController extends Controller
{
    use ApiHelper;
    public function index(Request $request)
    {
        $doyouknows = new GetList([
            'query'=>  DoYouKnow::query(),
            'timestamp'=> $request->timestamp,
            'type' => 'doyouknows',
        ]);

        return $this->succeed($doyouknows,200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Position;
use App\API\ApiHelper;
use App\Http\Resources\Api\GetList;

class PositionController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        $positions = new GetList([
            'query'=> Position::query(),
            'timestamp'=> $request->timestamp,
            'type' => 'positions',
        ]);

        return $this->succeed($positions,200);
    }
}

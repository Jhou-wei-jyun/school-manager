<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Position;
use App\API\ApiHelper;


class PositionController extends Controller
{
    use ApiHelper;


    public function SelectPosition()
    {
        return Position::whereNotIn('id',[1,10,20])
        ->get();
    }

}

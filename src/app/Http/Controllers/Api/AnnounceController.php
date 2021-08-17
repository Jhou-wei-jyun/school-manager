<?php

namespace App\Http\Controllers\Api;

use App\API\ApiHelper;
use Illuminate\Http\Request;
use App\Announcement;
use Carbon\Carbon;
use App\Achievement;

class AnnounceController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        $items = Announcement::where('school_id', $request->school_id)
            ->where('is_show', true)
            // ->select(['id','name'])
            ->orderBy('created_at', 'desc')
            ->take(3)->get();

        $items = $items->map(function ($item) {

            $collection =  collect([
                'id' => $item->id,
                'avatar' => $item->avatar,
            ]);

            return $collection;
        });


        return $items;
    }

}

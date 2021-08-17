<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\Category;
use App\Http\Resources\Api\GetList;

class CategoryController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        $categories = new GetList([
            'query'=> Category::query(),
            'timestamp'=> $request->timestamp,
            'type' => 'category',
            ]);

        return $this->succeed($categories,200);
    }

    public function show(Request $request)
    {

        $category_id = $request->category_id;
        $timestamp = $request->timestamp;
        $category = Category::find($category_id);

        if ($timestamp == 0){
            $category_items = $category->items()->orderBy('updated_at', 'asc')->get();
        }

        if ($timestamp != 0){
            $updated_at = date('Y-m-d H:i:s',$timestamp);
            $category_items = $category->items()->where('items.updated_at','>',$updated_at)
                ->orderBy('items.updated_at', 'asc')
                ->get();
        }

        $category_items->count() == 0 ? $timestamp : $timestamp = strtotime($category_items->last()['updated_at']);
        $category_items = $category_items->map(function($item){
            $item->photo = $item->photo_url;
            $item->area_id = $item->records()->get()->last()['area_id'];
            return $item;
        });
        return $this->succeed(['timestamp' => (int)$timestamp,'category_items' => $category_items],200);
    }
}

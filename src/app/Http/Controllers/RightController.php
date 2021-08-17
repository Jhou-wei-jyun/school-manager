<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use Illuminate\Http\Request;

use App\Admin;
use App\Group;
use App\Page;
use App\Tab;
use App\Block;

class RightController extends Controller
{

    use ApiHelper;

    public function PageRight(Request $request)
    {
        $group_id = (int) $request->group_id;
        $group = Group::where('group_id', $group_id)->with('pages')->first();
        $pages = $group->pages->map(function ($item) {
            $collection = collect([
                'page_name' => $item->page_name,
                'show' => $item->pivot->show,
            ]);
            return $collection;
        });

        return $pages;
    }
    public function TabRight(Request $request)
    {
        $group_id = (int) $request->group_id;
        $page_id = (int) $request->page_id;
        $group = Group::where('group_id', $group_id)->with(['tabs' => function ($query) use($page_id) {
            $query->where('page_id', $page_id);
        }])->first();
        $tabs = $group->tabs->map(function ($item) {
            $collection = collect([
                'tab_name' => $item->tab_name,
                'show' => $item->pivot->show,
            ]);
            return $collection;
        })->keyBy('tab_name');

        return $tabs;
    }
    public function BlockRight(Request $request)
    {
        $group_id = (int) $request->group_id;
        $tab_id = (int) $request->tab_id;
        $group = Group::where('group_id', $group_id)->with(['blocks' => function ($query) use($tab_id) {
            $query->where('tab_id', $tab_id);
        }])->first();
        $blocks = $group->blocks->map(function ($item) {
            $collection = collect([
                'block_name' => $item->block_name,
                'show' => $item->pivot->show,
            ]);
            return $collection;
        })->keyBy('block_name');

        return $blocks;
    }
    public function RightRelation(Request $request)
    {
        $data = Page::with('tabs.blocks')->get();
        return $data;
    }
    public function RightStore(Request $request)
    {
        $group_name = $request->group_name;
        $arr_page_id = $request->arr_page_id;
        $arr_tab_id = $request->arr_tab_id;
        $arr_block_id = $request->arr_block_id;

        $no_pages = Page::whereNotIn('page_id', $arr_page_id)->get();
        $no_tabs = Tab::whereNotIn('tab_id', $arr_tab_id)->get();
        $no_blocks = Block::whereNotIn('block_id', $arr_block_id)->get();

        $group = new Group;
        $group->group_name = $group_name;
        $group->save();
        $group->pages()->attach($arr_page_id, ['show' => true]);
        $group->tabs()->attach($arr_tab_id, ['show' => true]);
        $group->blocks()->attach($arr_block_id, ['show' => true]);
        $group->pages()->attach($no_pages, ['show' => false]);
        $group->tabs()->attach($no_tabs, ['show' => false]);
        $group->blocks()->attach($no_blocks, ['show' => false]);

        return $this->succeed(['group_name' => $group_name], 200);
    }
    public function GroupIndex(Request $request)
    {

        $groups = Group::all();

        return $groups;
    }
    public function GroupRightIndex(Request $request)
    {
        $group_id = $request->group_id;
        $group = Group::where('group_id', $group_id)->with('pages','tabs','blocks')->first();
        $pageData = [];
        $tabData = [];
        $blockData = [];
        foreach ($group->pages as $page) {
            if ($page->pivot->show == true){
                array_push($pageData, $page->page_id);
            }
        }
        foreach ($group->tabs as $tab) {
            if ($tab->pivot->show == true){
                array_push($tabData, $tab->tab_id);
            }
        }
        foreach ($group->blocks as $block) {
            if ($block->pivot->show == true){
                array_push($blockData, $block->block_id);
            }
        }

        return [
            "pageData" => $pageData,
            "tabData" => $tabData,
            "blockData" => $blockData,
        ];
    }
    public function RightEdit(Request $request)
    {
        $group_id = $request->group_id;
        $arr_page_id = $request->arr_page_id;
        $arr_tab_id = $request->arr_tab_id;
        $arr_block_id = $request->arr_block_id;
        $no_pages = Page::whereNotIn('page_id', $arr_page_id)->get();
        $no_tabs = Tab::whereNotIn('tab_id', $arr_tab_id)->get();
        $no_blocks = Block::whereNotIn('block_id', $arr_block_id)->get();
        $arr_page = [];
        $arr_tab = [];
        $arr_block = [];
        $arr_no_page = [];
        $arr_no_tab = [];
        $arr_no_block = [];
        foreach ($arr_page_id as $key=>$value) {
            //collect all inserted record IDs
            $arr_page[$value] = ['show' => true];
        }
        foreach ($arr_tab_id as $key=>$value) {
            //collect all inserted record IDs
            $arr_tab[$value] = ['show' => true];
        }
        foreach ($arr_block_id as $key=>$value) {
            //collect all inserted record IDs
            $arr_block[$value] = ['show' => true];
        }
        foreach ($no_pages as $key=>$value) {
            //collect all inserted record IDs
            $arr_no_page[$value->page_id] = ['show' => false];
        }
        foreach ($no_tabs as $key=>$value) {
            //collect all inserted record IDs
            $arr_no_tab[$value->tab_id] = ['show' => false];
        }
        foreach ($no_blocks as $key=>$value) {
            //collect all inserted record IDs
            $arr_no_block[$value->block_id] = ['show' => false];
        }

        $group = Group::where('group_id', $group_id)->first();
        $group->pages()->sync($arr_page, false);
        $group->tabs()->sync($arr_tab, false);
        $group->blocks()->sync($arr_block , false);
        $group->pages()->sync($arr_no_page, false);
        $group->tabs()->sync($arr_no_tab, false);
        $group->blocks()->sync($arr_no_block , false);

        return $this->succeed('', 200);
    }
    public function GroupDelete(Request $request)
    {
        $group_id = $request->group_id;
        $group = Group::where('group_id', $group_id)->first();
        $group->pages()->detach();
        $group->tabs()->detach();
        $group->blocks()->detach();
        Admin::where('group_id', $group_id)
        ->update([
            'group_id' => null,
        ]);
        $group->delete();
        return $this->succeed('', 200);
    }

}

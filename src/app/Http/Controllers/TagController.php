<?php

namespace App\Http\Controllers;

use App\Album;
use App\Tag;
use Illuminate\Http\Request;
use App\API\ApiHelper;
use App\Photo;
use App\User;

class TagController extends Controller
{
    use ApiHelper;

    public function tagIndex(Request $request)
    {
        $department_id = $request->department_id;
        $tags = Tag::where('department_id', $department_id)->select('id as tag_id', 'tag_name')->get();
        // return $tags;
        return $this->succeed($tags, 200);
    }
    public function userTagIndex(Request $request)
    {
        $department_id = $request->department_id;
        $userTags = User::where('department_id', $department_id)->with('profile')->get();
        $userTags = $userTags->map(function ($userTag) {
            $collection = collect([
                'user_tag_id' => $userTag->id,
                'user_tag_name' => $userTag->profile->name,
            ]);
            return $collection;
        });
        return $this->succeed($userTags, 200);
    }
    public function tagAdd(Request $request)
    {
        $tag_name = $request->tagName;
        $department_id = $request->department_id;
        $tagAdd = new Tag([
            'tag_name' => $tag_name,
            'department_id' => $department_id,
        ]);
        $tagAdd->save();
        return $this->succeed([
            'tag_id' => $tagAdd->id,
            'tag_name' => $tagAdd->tag_name,
        ], 200);
    }
    // public function tagAlbumSync(Request $request)
    // {
    //     $album_id = (int)$request->album_id;
    //     $tag_id = (array)$request->tag_id;
    //     $album = Album::find($album_id);
    //     $album->tags()->sync($tag_id);
    //     return $this->succeed($album, 200);
    // }
    public function tagAlbumSync(Request $request) //Album底下的所有Photos上加入Tag
    {
        $album_id = (int)$request->album_id;
        $tag_id = (array)$request->tag_id;
        $album = Album::find($album_id);
        $album->tags()->sync($tag_id);
        $photos = $album->photos;
        foreach ($photos as $key => $photo) {
            $photo->tags()->sync($tag_id);
        }

        return $this->succeed($photos, 200);
    }
    public function tagPhotoSync(Request $request)
    {
        $photo_id = (int)$request->photo_id;
        $tag_id = (array)$request->tag_id;
        $photo = Photo::find($photo_id);
        $photo->tags()->sync($tag_id);
        return $this->succeed($photo, 200);
    }
    // public function tagRemove(Request $request)
    // {
    //     $album_id = $request->album_id;
    //     $tag_id = $request->tag_id;
    //     $album = Album::find($album_id);
    //     $album->tags()->detach($tag_id);
    //     return $this->succeed($album, 200);
    // }
    public function tagDelete(Request $request)
    {
        $tag_id = (int)$request->tag_id;
        $albums = Album::all();
        foreach ($albums as $album) {
            $album->tags()->detach($tag_id);
        }
        $tag = Tag::find($tag_id);
        $tag->delete();

        return $this->succeed('移除成功', 200);
    }
    public function userTagPhotoSync(Request $request)
    {
        $photo_id = (int)$request->photo_id;
        $user_tag_id = (array)$request->user_tag_id;
        $photo = Photo::find($photo_id);
        $photo->userTags()->sync($user_tag_id);
        return $this->succeed($photo, 200);
    }
    public function getAllUserAvatar(Request $request)
    {
        $userAvatars = User::where('is_actived', true)->with('profile')->get()->pluck('profile.avatar');
        $userAvatars = $userAvatars->map(function ($userAvatar) {
            $userAvatar = 'avatar/' . $userAvatar;
            return $userAvatar;
        });
        return $userAvatars;
    }
    public function getAlbumTag(Request $request)
    {
        $album = Album::with('tags')->get();
        return $this->succeed($album, 200);
        // $books = App\Book::with('author')->get();

        // foreach ($books as $book) {
        //     echo $book->author->name;
        // }
    }
    public function getPhotoTag(Request $request)
    {
        $photo_id = $request->photo_id;
        $photo = Photo::where('photo_id', $photo_id)->with('tags')->first();

        $photo_tags = $photo->tags->map(function ($item) {
            $collection = collect([
                'tag_id' => $item->id,
                'tag_name' => $item->tag_name,
            ]);
            return $collection;
        });
        return $this->succeed($photo_tags, 200);
    }
    public function getPhotoUserTag(Request $request)
    {
        $photo_id = $request->photo_id;
        $photo = Photo::where('photo_id', $photo_id)->with('userTags.profile')->first();

        $user_photo_tags = $photo->userTags->map(function ($item) {
            $collection = collect([
                'user_tag_id' => $item->id,
                'user_tag_name' => $item->profile->name,
            ]);
            return $collection;
        });
        return $this->succeed($user_photo_tags, 200);
    }
}

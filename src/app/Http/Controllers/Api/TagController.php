<?php

namespace App\Http\Controllers\Api;

use App\Album;
use App\Tag;
use Illuminate\Http\Request;
use App\API\ApiHelper;
use App\Photo;
use App\User;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    use ApiHelper;
    public function photoHasTagIndex(Request $request)
    {
        $photo_id = (int)$request->photo_id;
        $photo = Photo::find($photo_id);
        $tags = $photo->tags()->get();
        $tags = $tags->map(function ($item) {
            $collection = collect([
                'tag_id' => $item->id,
                'tag_name' => $item->tag_name,
            ]);
            return $collection;
        });

        $userTags = $photo->userTags()->with('profile')->get();
        $userTags = $userTags->map(function ($item) {
            $collection = collect([
                'user_tag_id' => $item->id,
                'user_tag_name' => $item->profile->name,
            ]);
            return $collection;
        });

        return $this->succeed([
            'tags' => $tags,
            'userTags' => $userTags,
        ], 200);
    }

    public function albumTagIndex(Request $request)
    {
        $album_id = (int)$request->album_id;
        $albumTags = Album::find($album_id)->tags()->select('id as tag_id', 'tag_name')->get();
        $albumTags = $albumTags->map(function ($albumTag) {
            $collection = collect([
                'tag_id' => $albumTag->tag_id,
                'tag_name' => $albumTag->tag_name,
            ]);
            return $collection;
        });
        return $this->succeed($albumTags, 200);
    }
    public function userTagIndex(Request $request)
    {
        $user_id = (int)$request->user_id;
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $validator = Validator::make(
            [
                'user_id' => $user_id,
            ],
            [
                'user_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $user = User::find($user_id);
        if ($parent_id) {
            return $this->succeed([
                'user_tag_id' => $user->id,
                'user_tag_name' => $user->profile->name,
            ], 200);
        }
        if ($teacher_id) {
            $department_id = $user->department_id;
            $users = User::where('department_id', $department_id)->with('profile')->get();
            $users = $users->map(function ($user) {
                $collection = collect([
                    'user_tag_id' => $user->id,
                    'user_tag_name' => $user->profile->name,
                ]);
                return $collection;
            });
            return $this->succeed($users, 200);
        }
        return $this->error('parent_id or teacher_id  undefined', 250);
    }
    public function userTagSelectAlbum(Request $request)
    {
        $user_tag_id = (array)$request->user_tag_id;
        $filterPhotos = User::whereIn('id', $user_tag_id)->with(['photos' => function ($query) {
            $query->where('status', true);
        }])
            ->get()->pluck('photos')->collapse()->pluck('photo_id');
        $filterAlbum = Photo::where('imageable_type', 'App\Album')
            ->whereIn('photo_id', $filterPhotos)->get()
            ->pluck('imageable_id');
        $albums = Album::whereIn('album_id', $filterAlbum)
            ->where('status', true)->with(['photos' => function ($query) use ($filterPhotos) {
                $query->where('status', true)->whereIn('photo_id', $filterPhotos)
                    ->select('imageable_id', 'imageable_id as album_id', 'photo_id', 'path', 'status', 'created_at');
            }])
            ->get();
        return $this->succeed($albums, 200);
    }
    public function userTagSelectPhoto(Request $request)
    {
        $user_tag_id = (array)$request->user_tag_id;
        $album_id = $request->album_id;
        $filterPhotos = User::whereIn('id', $user_tag_id)->with(['photos' => function ($query) {
            $query->where('status', true);
        }])
            ->get()->pluck('photos')->collapse()->pluck('photo_id');

        $photos = Photo::where('imageable_type', 'App\Album')->where('imageable_id', $album_id)
            ->where('status', true)->whereIn('photo_id', $filterPhotos)
            ->select('imageable_id', 'imageable_id as album_id', 'photo_id', 'path', 'status', 'created_at')
            ->get();
        return $this->succeed($photos, 200);
    }
    public function tagIndex(Request $request)
    {
        $user_id = (int)$request->user_id;
        $validator = Validator::make(
            [
                'user_id' => $user_id,
            ],
            [
                'user_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $department_id = User::find($user_id)->department_id;
        $tags = Tag::where('department_id', $department_id)->select('id as tag_id', 'tag_name')->get();
        return $this->succeed($tags, 200);
    }
    public function tagAdd(Request $request)
    {
        $user_id = (int)$request->user_id;
        $validator = Validator::make(
            [
                'user_id' => $user_id,
            ],
            [
                'user_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $department_id = User::find($user_id)->department_id;
        $tag_name = $request->tagName;
        $tagAdd = new Tag([
            'tag_name' => $tag_name,
            'department_id' => $department_id,
        ]);
        $tagAdd->save();
        return $this->succeed($tagAdd, 200);
    }
    public function tagAlbumAdd(Request $request)
    {
        $album_id = (int)$request->album_id;
        $tag_id = (int)$request->tag_id;
        $album = Album::find($album_id);
        $album->tags()->attach($tag_id);
        return $this->succeed($album, 200);
    }
    public function tagAlbumRemove(Request $request)
    {
        $album_id = (int)$request->album_id;
        $tag_id = (int)$request->tag_id;
        $album = Album::find($album_id);
        $album->tags()->detach($tag_id);
        return $this->succeed($album, 200);
    }
    public function tagPhotoAdd(Request $request)
    {
        $photo_id = (int)$request->photo_id;
        $tag_id = (int)$request->tag_id;
        $photo = Photo::find($photo_id);
        $photo->tags()->attach($tag_id);
        return $this->succeed($photo, 200);
    }
    public function tagPhotoRemove(Request $request)
    {
        $photo_id = (int)$request->photo_id;
        $tag_id = (int)$request->tag_id;
        $photo = Photo::find($photo_id);
        $photo->tags()->detach($tag_id);
        return $this->succeed($photo, 200);
    }
    public function userTagPhotoAdd(Request $request)
    {
        $photo_id = (int)$request->photo_id;
        $user_tag_id = (int)$request->user_tag_id;
        $photo = Photo::find($photo_id);
        $photo->userTags()->attach($user_tag_id);
        return $this->succeed($photo, 200);
    }
    public function userTagPhotoRemove(Request $request)
    {
        $photo_id = (int)$request->photo_id;
        $user_tag_id = (int)$request->user_tag_id;
        $photo = Photo::find($photo_id);
        $photo->userTags()->detach($user_tag_id);
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

        return $this->succeed('移除成功', 200);
    }
}

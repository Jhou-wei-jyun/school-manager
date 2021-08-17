<?php

namespace App\Http\Controllers;



use App\Album;
use App\API\ImageCompress;
use App\API\ApiHelper;
use App\Jobs\PythonFaceRecognition;
use App\Photo;
use App\Profile;
use App\User;
// use App\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class AlbumController extends Controller
{
    use ApiHelper;
    use ImageCompress;

    // public function addSmall($path)
    // {
    //     $small_path = preg_split('/.jpg/', $path, -1, PREG_SPLIT_NO_EMPTY);
    //     return $small_path[0] . '_small.jpg';
    // }
    public function getAlbumInfo(Request $request)
    {
        $album_id = $request->album_id;
        $album = Album::find($album_id);
        return $this->succeed($album, 200);
    }
    public function getPhotoInfo(Request $request)
    {
        $photo_id = $request->photo_id;
        $photo = Photo::find($photo_id);
        $avatar_path = 'album/' . $photo->imageable_id . '/' . $photo->path;
        $avatar_small_path = 'album/' . $photo->imageable_id . '/small/' . $photo->path;
        $data = $this->showImageInfo($avatar_path);
        $data = (object)$data;
        $timezone = config('services.time_zone');

        return $this->succeed(collect([
            'path' => $avatar_small_path,
            'height' => $data->COMPUTED['Height'],
            'width' => $data->COMPUTED['Width'],
            'file_size' => $data->FileSize,
            'file_type' => $data->MimeType,
            'update_date_time' => Carbon::parse($data->FileDateTime, $timezone)->toDateTimeString(),
        ]), 200);
    }
    public function indexAlbum(Request $request)
    {
        $department_id = $request->department_id;
        $albums = Album::where('department_id', $department_id)
            ->where('status', true)
            ->with(['photos' => function ($query) {
                $query->where('status', true)->select('imageable_id', 'imageable_id as album_id', 'path', 'photo_id')
                    ->with(['tags' => function ($tagQuery) {
                        $tagQuery->select('id as tag_id', 'tag_name');
                    }])
                    ->with(['userTags' => function ($userTagQuery) {
                        $userTagQuery->with(['profile' => function ($profileQuery) {
                            $profileQuery->select('user_id', 'user_id as user_tag_id', 'name as user_tag_name');
                        }])->select('id');
                    }]);
            }])
            ->with(['tags' => function ($tagQuery) {
                $tagQuery->select('id as tag_id', 'tag_name');
            }])
            ->get();
        //選出該相簿內所有Tag

        $albums = $albums->map(function ($album) {
            $tags = $album->photos->pluck('tags')->collapse()->pluck('tag_id')->unique()->values();
            $userTags = $album->photos->pluck('userTags')->collapse()->pluck('id')->unique()->values();
            $album = collect($album)->put('photoTags', $tags)->put('photoUserTags', $userTags);

            return $album;
        });
        // ->groupBy('albumParent')
        return $this->succeed($albums, 200);
    }
    public function indexPhoto(Request $request)
    {
        $album_id = $request->album_id;
        // $photos = Photo::where('album_id', $album_id)
        //     ->where('status', true)
        //     ->get();
        $photos = Photo::where('imageable_type', 'App\Album')->where('imageable_id', $album_id)
            ->where('status', true)
            ->with(['tags' => function ($tagQuery) {
                $tagQuery->select('id as tag_id', 'tag_name');
            }])
            ->with(['userTags' => function ($userTagQuery) {
                $userTagQuery->with(['profile' => function ($profileQuery) {
                    $profileQuery->select('user_id', 'user_id as user_tag_id', 'name as user_tag_name');
                }])->select('id');
            }])
            ->get();
        $photos = $photos->map(function ($photo) use ($album_id) {
            $photo = collect($photo)->put('album_id', $album_id);
            return $photo;
        });
        // ->groupBy('albumParent')

        return $this->succeed($photos, 200);
    }
    public function newAlbum(Request $request)
    {
        $department_id = (int)$request->department_id;
        $albumParent = (int)$request->albumParent; // 0:parent
        $albumTitle = (string)$request->albumTitle;
        $albumDate = (string)$request->albumDate;
        if ($request->has('album_file')) {
            $validator = Validator::make(
                [
                    'album_file' => $request->album_file,
                ],
                [
                    'album_file' => 'mimes:jpg,jpeg',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
        }
        $album_file = $request->file('album_file');

        $albumAdd = new Album([
            'albumTitle' => $albumTitle,
            'albumDate' => $albumDate,
            'albumParent' => $albumParent,
            'department_id' => $department_id,
        ]);
        $albumAdd->save();
        // $tag_id = (array)$request->tag_id;
        // $albumAdd->tags()->sync($tag_id);
        //存取照片
        if ($album_file) {
            $storageFile = config('services.storage_file');
            $storage = config('services.storage');
            $timezone = config('services.time_zone');
            $current = Carbon::now($timezone)->timestamp;
            $avatar_name = "Album-" . $albumAdd->album_id . "-" . $current . ".jpg";
            $avatar_path = $storageFile . 'album/avatar/' . $avatar_name;
            $avatar_small_path = $storageFile . 'album/avatar/small/' . $avatar_name;
            $avatar_medium_path = $storageFile . 'album/avatar/medium/' . $avatar_name;
            $avatar_large_path = $storageFile . 'album/avatar/large/' . $avatar_name;
            Storage::disk($storage)->putFileAs($storageFile . 'album/avatar', $album_file, $avatar_name);
            $this->reSaveIMG($avatar_path);
            Storage::disk($storage)->makeDirectory($storageFile . 'album/avatar/small');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/avatar/medium');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/avatar/large');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $albumAdd->album_id);
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $albumAdd->album_id . '/small');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $albumAdd->album_id . '/medium');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $albumAdd->album_id . '/large');
            $this->compressSmallIMG($avatar_path, $avatar_small_path);
            $this->compressMediumIMG($avatar_path, $avatar_medium_path);
            $this->compressLargeIMG($avatar_path, $avatar_large_path);
            $albumAdd
                ->update([
                    'albumImage' => $avatar_name,
                ]);
        }
        return $this->succeed($albumAdd, 200);
    }
    public function deleteAlbum(Request $request)
    {
        $arr_album_id = (array)$request->album_id;
        foreach ($arr_album_id as $key => $value) {
            $album = Album::find($value);
            $album->update([
                'status' => false,
            ]);
            $album->photos()->update([
                'status' => false,
            ]);
            $this->deleteAlbumChild($value);
        }

        return $this->succeed('成功移至垃圾桶', 200);
    }
    public function deleteAlbumChild($album_id)
    {
        $albumChild = Album::where('albumParent', $album_id);
        $albumChild->update([
            'status' => false,
        ]);
        $arr_album_id = $albumChild->get();
        if ($arr_album_id->isEmpty()) { //無子物件時停止
            return $this->succeed('', 200);
        } else {
            $arr_album_id = $arr_album_id->pluck('album_id');
            foreach ($arr_album_id as $key => $value) {
                $album = Album::find($value);
                $album->update([
                    'status' => false,
                ]);
                $album->photos()->update([
                    'status' => false,
                ]);
                $this->deleteAlbumChild($value); //重複動作
            }
            return $this->succeed('', 200);
        }
    }
    public function deletePhoto(Request $request)
    {
        $arr_photo_id = (array)$request->photo_id;
        //status ->false
        Photo::whereIn('photo_id', $arr_photo_id)
            ->update([
                'status' => false,
            ]);
        return $this->succeed('', 200);
    }
    public function trashIndex(Request $request)
    {
        $department_id = (int)$request->department_id;
        $albums = Album::where('department_id', $department_id)
            ->where('status', false)
            ->get();
        $photos = Album::where('department_id', $department_id)
            ->with(['photos' => function ($query) {
                $query->where('status', false)->select('imageable_id', 'imageable_id as album_id', 'path', 'photo_id');
            }])
            ->get()->pluck('photos')->collapse();
        return [
            'album' => $albums,
            'photo' => $photos,
        ];
    }
    public function deletePhotoFromTrash(Request $request)
    {
        $arr_photo_id = (array)$request->photo_id;
        $photos = Photo::whereIn('photo_id', $arr_photo_id)
            ->get();
        $photos = $photos->map(function ($photo) {
            $collection = collect([
                'path' => '/album/' . $photo->imageable_id . '/' . $photo->path,
                'small_path' => '/album/' . $photo->imageable_id . '/small/' . $photo->path,
                'medium_path' => '/album/' . $photo->imageable_id . '/medium/' . $photo->path,
                'large_path' => '/album/' . $photo->imageable_id . '/large/' . $photo->path,
            ]);
            return $collection;
        });
        $storage = config('services.storage');
        Storage::disk($storage)->delete($photos->pluck('path')->toArray());
        Storage::disk($storage)->delete($photos->pluck('small_path')->toArray());
        Storage::disk($storage)->delete($photos->pluck('medium_path')->toArray());
        Storage::disk($storage)->delete($photos->pluck('large_path')->toArray());
        $photos = Photo::whereIn('photo_id', $arr_photo_id)->with('userTags')->with('tags')->get();
        foreach ($photos as $photo) {
            $photo->userTags()->detach();
            $photo->tags()->detach();
            $photo->delete();
        }
        return $this->succeed('', 200);
    }
    public function deleteAlbumFromTrash(Request $request)
    {
        $storage = config('services.storage');
        $arr_album_id = (array)$request->album_id;
        foreach ($arr_album_id as $key => $value) {
            //刪除底下圖片 不含底下相簿
            $album = Album::find($value);
            $photos = $album->photos()->with('userTags')->with('tags')->get();
            $photos_path = $photos->map(function ($photo) {
                $collection = collect([
                    'path' => '/album/' . $photo->imageable_id . '/' . $photo->path,
                    'small_path' => '/album/' . $photo->imageable_id . '/small/' . $photo->path,
                    'medium_path' => '/album/' . $photo->imageable_id . '/medium/' . $photo->path,
                    'large_path' => '/album/' . $photo->imageable_id . '/large/' . $photo->path,
                ]);
                return $collection;
            });
            Storage::disk($storage)->delete($photos_path->pluck('path')->toArray());
            Storage::disk($storage)->delete($photos_path->pluck('small_path')->toArray());
            Storage::disk($storage)->delete($photos_path->pluck('medium_path')->toArray());
            Storage::disk($storage)->delete($photos_path->pluck('large_path')->toArray());

            foreach ($photos as $photo) {
                $photo->userTags()->detach();
                $photo->tags()->detach();
                $photo->delete();
            }
            Storage::disk($storage)->delete('/album/avatar/' . $album->albumImage);
            Storage::disk($storage)->delete('/album/avatar/small/' . $album->albumImage);
            Storage::disk($storage)->delete('/album/avatar/medium/' . $album->albumImage);
            Storage::disk($storage)->delete('/album/avatar/large/' . $album->albumImage);
            $album->tags()->detach();
            $album->delete();
            //底下相簿移動至父層
            Album::where('albumParent', $value)
                ->update([
                    'albumParent' => 0,
                ]);
        }
        return $this->succeed('', 200);
    }
    public function restoreAlbumFromTrash(Request $request)
    {
        $arr_album_id = (array)$request->album_id;
        foreach ($arr_album_id as $key => $value) {
            $album = Album::find($value);
            $album->update([
                'status' => true,
            ]);
            $album->photos()->update([
                'status' => true,
            ]);
            $checkAlbumParent = Album::find($album->albumParent);
            if ($checkAlbumParent == null) {
                continue;
            } else {
                if ($checkAlbumParent->status == false) {
                    $album->update([
                        'albumParent' => 0,
                    ]);
                }
            }
        }
        return $this->succeed('', 200);
    }
    public function restorePhotoFromTrash(Request $request)
    {
        $arr_photo_id = (array)$request->photo_id;
        foreach ($arr_photo_id as $key => $value) {
            $photo = Photo::find($value);
            $photo->update([
                'status' => true,
            ]);
            $photo->imageable()->update([
                'status' => true,
            ]);
            $checkAlbumParent = Album::find($photo->imageable->albumParent);
            if ($checkAlbumParent == null) {
                continue;
            } else {
                if ($checkAlbumParent->status == false) {
                    $photo->imageable()->update([
                        'albumParent' => 0,
                    ]);
                }
            }
        }
        return $this->succeed('', 200);
    }
    public function newPhoto(Request $request)
    {
        $album_id = (int)$request->album_id;
        $album = Album::find($album_id);
        foreach ($request->file('album_files') as $album_file) {
            $validator = Validator::make(
                [
                    'album_file' => $album_file,
                ],
                [
                    'album_file' => 'mimes:jpg,jpeg',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }

            // $album = Album::find($album_id);
            $photoAdd = new Photo;
            // $photoAdd->album_id = $album->album_id;
            $photoAdd->imageable_id = $album->album_id;
            $photoAdd->imageable_type = 'App\Album';
            $photoAdd->save();
            $storage = config('services.storage');
            $storageFile = config('services.storage_file');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $album->album_id . '/small');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $album->album_id . '/medium');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $album->album_id . '/large');
            //存取照片
            if ($album_file) {
                $timezone = config('services.time_zone');
                $current = Carbon::now($timezone)->timestamp;
                $avatar_name = "Album-" . $album->album_id . "-" . $current . ".jpg";
                $avatar_path = $storageFile . 'album/' . $album->album_id . '/' . $avatar_name;
                $avatar_small_path = $storageFile . 'album/' . $album->album_id . '/small/' . $avatar_name;
                $avatar_medium_path = $storageFile . 'album/' . $album->album_id . '/medium/' . $avatar_name;
                $avatar_large_path = $storageFile . 'album/' . $album->album_id . '/large/' . $avatar_name;
                Storage::disk($storage)->putFileAs($storageFile . 'album/' . $album->album_id . '/', $album_file, $avatar_name);
                $this->reSaveIMG($avatar_path);
                $this->compressSmallIMG($avatar_path, $avatar_small_path);
                $this->compressMediumIMG($avatar_path, $avatar_medium_path);
                $this->compressLargeIMG($avatar_path, $avatar_large_path);
                $photoAdd
                    ->update([
                        'path' => $avatar_name,
                    ]);
                //加入自動Tag隊列
                $job = (new PythonFaceRecognition($photoAdd->photo_id, $avatar_path, $avatar_name, $album->department_id))->onConnection('redis');
                $this->dispatch($job);
            }
            sleep(1); //防止名稱重複
            continue;
        }
        return $this->succeed('', 200);
        // $album_file = $request->file('album_file');

    }
    public function allPhotoSmall()
    {
        set_time_limit(0);
        $users = User::where('is_actived', true)->with('profile')->get();
        $users = $users->map(function ($user) {
            $collection = collect([
                'avatar' =>  $user->profile->avatar,
            ]);
            return $collection;
        })->pluck('avatar');

        $result = collect([
            'avatar' =>  $users,
            'count' => $users->count(),
        ]);
        $storage = config('services.storage');
        $storageFile = config('services.storage_file');
        foreach ($users as $user) {
            Storage::disk($storage)->makeDirectory($storageFile . 'avatar/small');
            $avatar_path = $storageFile . 'avatar/' . $user;
            $avatar_small_path = $storageFile . 'avatar/small/' . $user;
            $this->compressSmallIMG($avatar_path, $avatar_small_path);
        }

        return $this->succeed($result, 200);
    }
    public function avatarRename()
    {
        $users = Profile::select('profile_id', 'avatar')->get();

        $pattern = "/avatar\//i";
        $change_profile_arr = [];
        $users = $users->each(function ($user) use ($pattern) {
            $result = preg_replace($pattern, "", $user->avatar);
            Profile::find($user->profile_id)
                ->update([
                    "avatar" => $result,
                ]);
        });
        $result = collect([
            'avatar' =>  $users,
            'count' => $users->count(),
        ]);


        return $this->succeed($result, 200);
    }
    public function editAlbum(Request $request)
    {
        $album_id = $request->album_id;
        $album_title = $request->album_title;
        $album_date = $request->album_date;
        $album = tap(Album::find($album_id))
            ->update([
                "albumTitle" => $album_title,
                "albumDate" => $album_date,
            ]);
        return $this->succeed(json_decode($album), 200);
    }
}

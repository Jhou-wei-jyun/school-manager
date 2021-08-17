<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

// use App\Tag;
use App\Album;
use App\API\ImageCompress;
use App\API\ApiHelper;
use App\Department;
use App\Jobs\PythonFaceRecognition;
use App\Photo;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class AlbumController extends Controller
{
    use ApiHelper;
    use ImageCompress;

    function escape_like_str($str)
    {
        $like_escape_char = '!';

        return str_replace([$like_escape_char, '%', '_'], [
            $like_escape_char . $like_escape_char,
            $like_escape_char . '%',
            $like_escape_char . '_',
        ], $str);
    }

    // public function addSmall($path)
    // {
    //     $small_path = preg_split('/.jpg/', $path, -1, PREG_SPLIT_NO_EMPTY);
    //     return $small_path[0] . '_small.jpg';
    // }
    public function indexAlbum(Request $request)
    {
        $user_id = $request->user_id;
        $department_id = User::find($user_id)->department_id;
        $albums = Album::where('department_id', $department_id)
            ->where('status', true)->with(['photos' => function ($query) {
                $query->where('status', true)->select('imageable_id', 'imageable_id as album_id', 'path', 'photo_id');
            }])
            ->get();
        // ->groupBy('albumParent')
        return $albums;
    }
    public function indexPhoto(Request $request)
    {
        $album_id = $request->album_id;
        $photos = Photo::where('imageable_type', 'App\Album')->where('imageable_id', $album_id)
            ->where('status', true)
            ->select('imageable_id', 'imageable_id as album_id', 'photo_id', 'path', 'status', 'created_at')
            ->get();
        // ->groupBy('albumParent')
        return $photos;
    }
    public function newAlbum(Request $request)
    {
        $user_id = $request->user_id;
        $albumParent = (int)$request->albumParent; // 0:parent
        $albumTitle = (string)$request->albumTitle;
        $albumDate = (string)$request->albumDate;
        $photo = (string)$request->photo;
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
        if ($photo) {
            $storageFile = config('services.storage_file');
            $storage = config('services.storage');
            $timezone = config('services.time_zone');
            $current = Carbon::now($timezone)->timestamp;
            $avatar_name = "Album-" . $albumAdd->album_id . "-" . $current . ".jpg";
            $avatar_path = $storageFile . 'album/avatar/' . $avatar_name;
            $avatar_small_path = $storageFile . 'album/avatar/small/' . $avatar_name;
            $avatar_medium_path = $storageFile . 'album/avatar/medium/' . $avatar_name;
            $avatar_large_path = $storageFile . 'album/avatar/large/' . $avatar_name;

            //base64 decode
            $extension = explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($photo, 0, strpos($photo, ',') + 1);
            // find substring fro replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $photo);
            $image = str_replace(' ', '+', $image);
            Storage::disk('public')->put($avatar_name, base64_decode($image));
            Storage::move('public/' . $avatar_name, 'album/avatar/' . $avatar_name);
            // Storage::disk($storage)->putFileAs($storageFile . 'album/avatar', $album_file, $avatar_name);
            Storage::disk($storage)->makeDirectory($storageFile . 'album/avatar/small');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/avatar/medium');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/avatar/large');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $albumAdd->album_id);
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $albumAdd->album_id . '/small');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $albumAdd->album_id . '/medium');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $albumAdd->album_id . '/large');
            $this->reSaveIMG($avatar_path);
            $this->compressSmallIMG($avatar_path, $avatar_small_path);
            $this->compressMediumIMG($avatar_path, $avatar_medium_path);
            $this->compressLargeIMG($avatar_path, $avatar_large_path);
            $albumAdd
                ->update([
                    'albumImage' => $avatar_name,
                ]);
        }
        return $this->succeed('', 200);
    }

    // public function deleteAlbum(Request $request)
    // {
    //     $arr_album_id = (array)$request->album_id;
    //     foreach ($arr_album_id as $key => $value) {
    //         $album = Album::find($value);
    //         $album->update([
    //             'status' => false,
    //         ]);
    //         $album->photos()->update([
    //             'status' => false,
    //         ]);
    //         $this->deleteAlbumChild($value);
    //     }

    //     return $this->succeed('', 200);
    // }
    // public function deleteAlbumChild($album_id)
    // {
    //     $albumChild = Album::where('albumParent', $album_id);
    //     $albumChild->update([
    //         'status' => false,
    //     ]);
    //     $arr_album_id = $albumChild->get();
    //     if ($arr_album_id->isEmpty()) { //無子物件時停止
    //         return $this->succeed('', 200);
    //     } else {
    //         $arr_album_id = $arr_album_id->pluck('album_id');
    //         foreach ($arr_album_id as $key => $value) {
    //             $album = Album::find($value);
    //             $album->update([
    //                 'status' => false,
    //             ]);
    //             $album->photos()->update([
    //                 'status' => false,
    //             ]);
    //             $this->deleteAlbumChild($value); //重複動作
    //         }
    //         return $this->succeed('', 200);
    //     }
    // }
    // public function deletePhoto(Request $request)
    // {
    //     $arr_photo_id = (array)$request->photo_id;
    //     //status ->false
    //     Photo::whereIn('photo_id', $arr_photo_id)
    //         ->update([
    //             'status' => false,
    //         ]);
    //     return $this->succeed('', 200);
    // }
    // public function trashIndex(Request $request)
    // {
    //     $department_id = (int)$request->department_id;
    //     $albums = Album::where('department_id', $department_id)
    //         ->where('status', false)
    //         ->get();
    //     $photos = Album::where('department_id', $department_id)
    //         ->with(['photos' => function ($query) {
    //             $query->where('status', false);
    //         }])
    //         ->get()->pluck('photos')->collapse();
    //     return [
    //         'album' => $albums,
    //         'photo' => $photos,
    //     ];
    // }
    public function deletePhoto(Request $request)
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
    public function deleteAlbum(Request $request)
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
    // public function restoreAlbumFromTrash(Request $request)
    // {
    //     $arr_album_id = (array)$request->album_id;
    //     foreach ($arr_album_id as $key => $value) {
    //         $album = Album::find($value);
    //         $album->update([
    //             'status' => true,
    //         ]);
    //         $album->photos()->update([
    //             'status' => true,
    //         ]);
    //         $checkAlbumParent = Album::find($album->albumParent);
    //         if ($checkAlbumParent == null) {
    //             continue;
    //         } else {
    //             if ($checkAlbumParent->status == false) {
    //                 $album->update([
    //                     'albumParent' => 0,
    //                 ]);
    //             }
    //         }
    //     }
    //     return $this->succeed('', 200);
    // }
    // public function restorePhotoFromTrash(Request $request)
    // {
    //     $arr_photo_id = (array)$request->photo_id;
    //     foreach ($arr_photo_id as $key => $value) {
    //         $photo = Photo::find($value);
    //         $photo->update([
    //             'status' => true,
    //         ]);
    //         $photo->album()->update([
    //             'status' => true,
    //         ]);
    //         $checkAlbumParent = Album::find($photo->album->albumParent);
    //         if ($checkAlbumParent == null) {
    //             continue;
    //         } else {
    //             if ($checkAlbumParent->status == false) {
    //                 $photo->album()->update([
    //                     'albumParent' => 0,
    //                 ]);
    //             }
    //         }
    //     }
    //     return $this->succeed('', 200);
    // }
    public function newPhoto(Request $request)
    {
        $album_id = (int)$request->album_id;
        $album = Album::find($album_id);
        $photos = (array)json_decode($request->photos, true);

        foreach ($photos as $photo) {
            $validator = Validator::make(
                [
                    'photo' => $photo,
                ],
                [
                    'photo' => 'required',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('fails', $validator->errors(), 401);
            }

            // $album = Album::find($album_id);
            $photoAdd = new Photo;
            // $photoAdd->album_id = $album->album_id;
            $photoAdd->imageable_id = $album->album_id;
            $photoAdd->imageable_type = 'App\Album';
            $photoAdd->save();
            $storageFile = config('services.storage_file');
            $storage = config('services.storage');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $album->album_id . '/small');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $album->album_id . '/medium');
            Storage::disk($storage)->makeDirectory($storageFile . 'album/' . $album->album_id . '/large');
            //存取照片
            if ($photo) {
                $timezone = config('services.time_zone');
                $current = Carbon::now($timezone)->timestamp;
                $avatar_name = "Album-" . $album->album_id . "-" . $current . ".jpg";
                $avatar_path = $storageFile . 'album/' . $album->album_id . '/' . $avatar_name;
                $avatar_small_path = $storageFile . 'album/' . $album->album_id . '/small/' . $avatar_name;
                $avatar_medium_path = $storageFile . 'album/' . $album->album_id . '/medium/' . $avatar_name;
                $avatar_large_path = $storageFile . 'album/' . $album->album_id . '/large/' . $avatar_name;
                //base64 decode
                $extension = explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];   // .jpg .png .pdf
                $replace = substr($photo, 0, strpos($photo, ',') + 1);
                // find substring fro replace here eg: data:image/png;base64,
                $image = str_replace($replace, '', $photo);
                $image = str_replace(' ', '+', $image);
                Storage::disk('public')->put($avatar_name, base64_decode($image));
                Storage::move('public/' . $avatar_name, 'album/' . $album->album_id . '/' . $avatar_name);
                // Storage::disk($storage)->putFileAs($storageFile . 'album/' . $album->album_id . '/', $album_file, $avatar_name);
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
    public function editalbum(Request $request)
    {
        $album_id = $request->album_id;
        $albumTitle = (string)$request->albumTitle;
        $albumDate = (string)$request->albumDate;
        $photo = (string)$request->photo;
        $validator = Validator::make(
            [
                'album_id' => $album_id,
                'albumTitle' => $albumTitle,
                'albumDate' => $albumDate,
            ],
            [
                'album_id' => 'required',
                'albumTitle' => 'required',
                'albumDate' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }

        $album = Album::find($album_id);
        $keep_orgin_path = $album->albumImage;
        try {
            $album->update([
                'albumTitle' => $albumTitle,
                'albumDate' => $albumDate,
            ]);
            if ($photo) {
                $storageFile = config('services.storage_file');
                $storage = config('services.storage');
                $timezone = config('services.time_zone');
                $current = Carbon::now($timezone)->timestamp;
                $avatar_name = "Album-" . $album->album_id . "-" . $current . ".jpg";
                $avatar_path = $storageFile . 'album/avatar/' . $avatar_name;
                $avatar_small_path = $storageFile . 'album/avatar/small/' . $avatar_name;
                $avatar_medium_path = $storageFile . 'album/avatar/medium/' . $avatar_name;
                $avatar_large_path = $storageFile . 'album/avatar/large/' . $avatar_name;
                //base64 decode
                $extension = explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];   // .jpg .png .pdf
                $replace = substr($photo, 0, strpos($photo, ',') + 1);
                // find substring fro replace here eg: data:image/png;base64,
                $image = str_replace($replace, '', $photo);
                $image = str_replace(' ', '+', $image);
                Storage::disk('public')->put($avatar_name, base64_decode($image));
                Storage::move('public/' . $avatar_name, 'album/avatar/' . $avatar_name);
                $this->reSaveIMG($avatar_path);
                $this->compressSmallIMG($avatar_path, $avatar_small_path);
                $this->compressMediumIMG($avatar_path, $avatar_medium_path);
                $this->compressLargeIMG($avatar_path, $avatar_large_path);

                // Get the updated rows count here. Keep in mind that zero is a
                // valid value (not failure) if there were no updates needed
                $album->update([
                    'albumImage' => $avatar_name,
                ]);

                Storage::disk($storage)->delete('/album/avatar/' . $keep_orgin_path);
                Storage::disk($storage)->delete('/album/avatar/small/' . $keep_orgin_path);
                Storage::disk($storage)->delete('/album/avatar/medium/' . $keep_orgin_path);
                Storage::disk($storage)->delete('/album/avatar/large/' . $keep_orgin_path);
            }
            return $this->succeed('更新成功', 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error('更新失敗', 401);
            // Do whatever you need if the query failed to execute
        }
    }
    public function selectAlbum(Request $request)
    {
        $user_id = $request->user_id;
        $department_id = User::find($user_id)->department_id;
        // $filters = json_encode($request->filters;
        // $filters = json_decode($filters true);
        $filters = json_decode($request->filters, true);

        $keyword = (string)$filters['keyword'];
        $date = (string)$filters['date'];
        $albums = Album::where('department_id', $department_id)->where('status', true)
            ->where('albumTitle', 'like', "%" . $this->escape_like_str($keyword) . "%")
            ->where(function ($query) use ($date) { //符合2者
                $query->where('albumDate', $date == null ? '!=' : '=', $date);
            })
            ->with(['photos' => function ($query) {
                $query->where('status', true)->select('imageable_id', 'imageable_id as album_id', 'path', 'photo_id');
            }])
            ->get();

        return $albums;
    }
}

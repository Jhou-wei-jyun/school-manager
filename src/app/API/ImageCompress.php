<?php

namespace App\API;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait ImageCompress
{
    protected $storage_path;

    public function __construct()
    {
        $this->storage_path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
    }
    public function reSaveIMG($path)
    {
        Image::make($this->storage_path . $path)
            ->orientate()
            ->save($this->storage_path . $path);
    }
    public function showImageInfo($path)
    {
        $data = Image::make($this->storage_path . $path)
            ->exif();
        return $data;
    }
    public function compressSmallIMG($path, $small_path)
    {
        Image::make($this->storage_path . $path)
            ->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->orientate()
            ->save($this->storage_path . $small_path);
    }
    public function compressMediumIMG($path, $medium_path)
    {
        Image::make($this->storage_path . $path)
            ->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->orientate()
            ->save($this->storage_path . $medium_path);
    }
    public function compressLargeIMG($path, $large_path)
    {
        Image::make($this->storage_path . $path)
            ->resize(2000, 2000, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->orientate()
            ->save($this->storage_path . $large_path);
    }
    // public function rotateTo0($file)
    // {
    //     $exif = exif_read_data($file);
    //     return $exif;
    //     // if(!empty($exif['Orientation'])) {
    //     //     switch($exif['Orientation']) {
    //     //         case 8:
    //     //             $image = imagerotate($image,90,0);
    //     //             break;
    //     //         case 3:
    //     //             $image = imagerotate($image,180,0);
    //     //             break;
    //     //         case 6:
    //     //             $image = imagerotate($image,-90,0);
    //     //             break;
    //     //     }
    //     // }
    // }
}

<?php
namespace App\Services\Image;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    public $url;
    public $name;
    protected $image;
    protected $path;

    // Paths

    public static $AVATARS_FOLDER = 'avatars';

    public static $SUCCESS_SAVED = 0;
    public static $NOT_SAVED = 1;

    
    public function createImageFromPath($image, $folder,  $name)
    {
        $this->path = $folder;
        $this->image = Image::make($image);
        $imageMimeExploded = explode('/', $this->image->mime());
        $ext = array_last($imageMimeExploded);
        $name = $this->check_image_with_the_same_name($name, $ext);
        $this->name = $name.'.'.$ext;
        $this->url = Storage::disk('public')->url($folder).'/'.$name.'.'.$ext;
        if ($this->image->save(base_path($this->url))) return self::$SUCCESS_SAVED;
        return self::$NOT_SAVED;
    }
    
    public function check_image_with_the_same_name($name, $ext)
    {
        $countNames = 0;
        $tmpName = $name;
        while(Storage::disk('public')->has($this->path.'/'.$tmpName.'.'.$ext)) {
            $countNames++;
            $tmpName = $name.$countNames;
        }
            return $name;
    }

    public static function getImage($folder, $file) {
        return Storage::disk('public')->get($folder.'/'.$file);
    }
}
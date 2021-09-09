<?php


namespace App\Helpers;


use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function uploadFiles($files, $path)
    {
        $filesArray = array();
        try {
            if (isset($files) && is_array($files))
                foreach ($files as $file) {
                    $filename = $file->store($path);
                    // $filename = asset('storage/'.$filename);
                    array_push($filesArray, $filename);
                }
        } catch (\Exception $ex) {
            return false;
        }
        return $filesArray;
    }

    public static function removeFiles($images_paths)
    {
        foreach ($images_paths as $image) {
            Storage::delete($image);
        }
    }

    public static function getFiles($directory_path)
    {
        return Storage::files($directory_path);
    }

    public static function getAllFiles($directory_path)
    {
        return Storage::allFiles($directory_path);
    }


    public static function zipProjectFiles($projectFiles){
        //create zips folder if it does not exists
        if(!File::exists(public_path('zips/'))) {
            File::makeDirectory(public_path('zips/'), $mode = 0777, true, true);
        }
        //fix files path
        $files = collect($projectFiles)->map(function($value){
            return public_path('storage/' . $value);
        })->toArray();
        //make zip file
        $zipName = \Str::uuid().".zip";
        $zipName = public_path('zips/') . $zipName;
        $zip = new ZipArchive;
        if($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE)){
            foreach ($files as $key => $file) {
                $zip->addFile($file,$projectFiles[$key]);
            }
            $zip->close();
        }
        return $zipName;
    }
}

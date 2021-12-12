<?php
namespace Zekini\CrudGenerator\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesFile
{
    
    /**
     * Disk to use for image processing
     *
     * @var string
     */
    protected $disk = 'public';

    
    /**
     * Get File
     *
     * @param  UploadedFile $files
     * @return string
     */
    public function getFile($files)
    {

        $filesArr = [];

        foreach($files as $file){
            $filepath = $file->store($this->disk);
            $filePathArr = explode('/', $filepath);
            $filesArr[] = $filePathArr[array_key_last($filePathArr)];
        }

        return json_encode($filesArr);
    }
    
    /**
     * Deleted an uploaded file
     *
     * @param  array $url
     * @param  string $disk
     * @return void
     */
    public function deleteFile($urls, $disk=null)
    {

        $disk = $disk ?? $this->disk;
        foreach(json_decode($urls) as $url){
            Storage::disk($disk)->delete($url);
        }
       
    }
}
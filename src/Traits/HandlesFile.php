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
     * @param  UploadedFile $image
     * @return string
     */
    public function getFile($image)
    {
        $filepath = $image->store($this->disk);
        $filePathArr = explode('/', $filepath);
        return $filePathArr[array_key_last($filePathArr)];
    }
    
    /**
     * Deleted an uploaded file
     *
     * @param  string $url
     * @param  string $disk
     * @return void
     */
    public function deleteFile($url, $disk=null)
    {
        $disk = $disk ?? $this->disk;
        Storage::disk($disk)->delete($url);
    }
}
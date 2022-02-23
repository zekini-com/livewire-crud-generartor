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
        if (is_string($files)) {
            return $files;
        }
        
        $filesArr = [];

        foreach ($files as $file) {
            $filepath = $file->store($this->disk);
            $filePathArr = explode('/', $filepath);
            $filesArr[] = $filePathArr[array_key_last($filePathArr)];
        }

        return json_encode($filesArr);
    }

    /**
     * Deleted an uploaded file
     */
    public function deleteFile($urls, string $disk = null): void
    {
        $disk = $disk ?? $this->disk;

        $urls_decoded = json_decode($urls);

        if (blank($urls_decoded)) {
            return;
        }

        foreach ($urls_decoded as $url) {
            if (Storage::disk($disk)->exists($url)) {
                Storage::disk($disk)->delete($url);
            }
        }
    }
}

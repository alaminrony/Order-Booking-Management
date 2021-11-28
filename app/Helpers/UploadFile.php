<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Storage;

trait UploadFile
{
    public function upload($file, $name)
    {
        return Storage::disk('public')->putFile('uploads/attachments', $file->file($name));
    }
}

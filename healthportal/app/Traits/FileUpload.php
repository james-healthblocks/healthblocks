<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;


trait FileUpload
{
    public function uploadFile($request, $directory='user_images', $filename='usrimage'){
        $file = $request->file($filename);
        if ($file && $file->isValid()){
            return $file->store($directory, 'public');
        }
        return null;
    }

    public function deleteImage(){
        if ($this->image){
            Storage::disk('public')->delete($this->image);
        }
    }
}
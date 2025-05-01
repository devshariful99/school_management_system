<?php

namespace App\Http\Traits;

use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait FileManagementTrait
{
    /**
     * Handle image upload for any model.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $model The model to attach the image to
     * @param string $imageField The name of the image field in the form
     * @param string $folderName The folder to store the image
     * @return void
     */
    public function handleFileUpload(Request $request, $model, $file_name, $fileField = 'image', $folderName = 'uploads/')
    {
        // Check if the request has a file
        if ($request->hasFile($fileField)) {
            $file = $request->file($fileField);
            $fileName = $file_name . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folderName, $fileName, 'public');

            // If the model already has an file, delete the old one
            if ($model->$fileField) {
                $this->fileDelete($model->$fileField);
            }
            // Assign the new image path to the model
            $model->$fileField = $path;
        }
    }
    public function fileDelete($file)
    {
        if ($file) {
            Storage::disk('public')->delete($file);
        }
    }

    public function handleFilepondFileUpload($model, $image, $old_image = false)
    {
        $temp_file = TempFile::findOrFail($image);
        if ($temp_file) {
            $from_path = 'public/' . $temp_file->path . '/' . $temp_file->filename;
            $to_path = 'admins/' . str_replace(' ', '-', admin()->name) . '/' . time() . '/' . $temp_file->filename;
            Storage::move($from_path, 'public/' . $to_path);
            if ($old_image) {
                // $this->fileDelete($old_image);
                $temp_create = new TempFile();
                $temp_create->path =  dirname($old_image);
                $temp_create->filename = basename($old_image);
                $temp_create->from()->associate($model);
                $temp_create->creater()->associate(admin());
                $temp_create->save();
            }
            $model->image = $to_path;
            Storage::deleteDirectory('public/' . $temp_file->path);
            $temp_file->forceDelete();
        }
    }
}
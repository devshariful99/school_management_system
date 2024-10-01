<?php

namespace App\Http\Traits;

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
}

<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function uploadTempFile(Request $request)
    {
        if ($request->hasFile($request->name)) {
            $file = $request->file($request->name);
            $filename = $file->getClientOriginalName();
            $folder = uniqid();
            $file->storeAs('file/tmp/' . $folder, $filename, 'public');
            $path = "file/tmp/" . $folder;

            $save = new TempFile();
            $save->path = $path;
            $save->filename = $filename;
            $save->created_at = Carbon::now()->toDateTimeString();
            $creater = $this->getCreator($request->creatorType);
            $save->creater()->associate($creater);
            $save->save();
            return $save->id;
        }
        return $request->name;
    }

    public function deleteTempFile()
    {

        $temp_file = TempFile::findOrFail(request()->getContent());
        if ($temp_file) {
            Storage::deleteDirectory('public/' . $temp_file->path);
            $id = $temp_file->id;
            $temp_file->forceDelete();
            return response()->json(['message' => 'Revert success', 'id' => $id]);
        }
    }

    // public function cleanupTempFiles(Request $request)
    // {
    //     $tempFiles = TempFile::where('created_at', '<', now()->subHours(1))->get();
    //     // Delete files older than 1 hour (adjust as needed)
    //     foreach ($tempFiles as $file) {
    //         // Delete the file from storage
    //         $filePath = storage_path("app/tmp/{$file->file_name}");
    //         if (file_exists($filePath)) {
    //             unlink($filePath); // Remove file
    //             $file->delete(); // Remove entry from the database (if applicable)
    //         }
    //     }

    //     return response()->json(['message' => 'Orphaned temporary files cleaned up']);
    // }



    public function deleteUnsavedTempFiles(Request $request)
    {
        $tempFileIds = $request->input('tempFileIds');
        foreach ($tempFileIds as $fileId) {
            $temp_file = TempFile::where('id', $fileId)->first();
            if ($temp_file) {
                Storage::deleteDirectory('public/' . $temp_file->path);
                $temp_file->forceDelete();
            } else {
                return response()->json(['message' => 'Temporary files not found']);
            }
        }

        return response()->json(['message' => 'Temporary files cleaned up successfully']);
    }




    private function getCreator($creatorType)
    {
        switch ($creatorType) {
            case 'user':
                return user();
            case 'admin':
                return admin();
        }
    }
}

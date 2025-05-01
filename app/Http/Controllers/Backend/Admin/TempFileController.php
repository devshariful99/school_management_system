<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TempFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('permission:temp-list|temp-delete|temp-download', ['only' => ['index']]);
        $this->middleware('permission:temp-download', ['only' => ['download']]);
        $this->middleware('permission:temp-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $temps = TempFile::with(['creater', 'from'])->get();
        if ($request->ajax()) {
            $temps = $temps->sortBy('sort_order');
            return DataTables::of($temps)
                ->editColumn('path', function ($temp) {
                    if (isImage($temp->filename)) {
                        return "<div class='imagePreviewDiv d-inline-block'>
                            <div id='lightbox' class='lightbox'>
                                <div class='lightbox-content'>
                                    <img src='" . storage_url($temp->path . '/' . $temp->filename) . "' class='lightbox_image'>
                                </div>
                                <div class='close_button fa-beat'>X</div>
                            </div>
                        </div>";
                    } else {
                        return "<a class='btn btn-info btn-sm'
                            href='javascript:void(0)'><i class='icon-doc fs-3'></i></a>";
                    }
                })
                ->editColumn('filename', function ($temp) {
                    return "<a class='btn btn-info btn-sm'
                            href='" . route('temp.download', encrypt($temp->path . '/' . $temp->filename)) . "'><i class='icon-arrow-down-circle fs-3 mt-1'></i></a>";
                })
                ->editColumn('created_at', function ($temp) {
                    return timeFormat($temp->created_at);
                })
                ->editColumn('created_by', function ($temp) {
                    return creater_name($temp->creater);
                })
                ->editColumn('from_type', function ($temp) {
                    return $temp->from_type ? getSubmitterType($temp->from_type) : 'Temp File';
                })
                ->editColumn('action', function ($temp) {
                    $menuItems = [
                        [
                            'routeName' => 'temp.destroy',
                            'params' => [encrypt($temp->id)],
                            'label' => 'Delete',
                            'delete' => true,
                            'permissions' => ['temp-delete']
                        ]

                    ];
                    return view('components.backend.admin.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['path', 'filename', 'created_at', 'created_by', 'from_type', 'action'])
                ->make(true);
        }
        return view('backend.admin.temp_file.index', compact('temps'));
    }

    public function download($file_url)
    {
        $file_url = decrypt($file_url);
        if (Storage::exists('public/' . $file_url)) {
            $fileExtension = pathinfo($file_url, PATHINFO_EXTENSION);

            // if (strtolower($fileExtension) === 'pdf') {
            //     return response()->file(storage_path('app/public/' . $file_url), [
            //         'Content-Disposition' => 'inline; filename="' . basename($file_url) . '"'
            //     ]);
            // } else {
            return response()->download(storage_path('app/public/' . $file_url), basename($file_url));
            // }
        } else {
            session()->flash('error', 'File not found!');
            return redirect()->route('temp.index');
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($id === 'all') {
            $temp_files = TempFile::all();
            foreach ($temp_files as $file) {
                Storage::deleteDirectory('public/' . $file->path);
                $file->forceDelete();
            }
        } else {
            $temp_file = TempFile::findOrFail(decrypt($id));
            Storage::deleteDirectory('public/' . $temp_file->path);
            $temp_file->forceDelete();
        }

        session()->flash('success', 'File deleted successfully');
        return redirect()->route('temp.index');
    }
}
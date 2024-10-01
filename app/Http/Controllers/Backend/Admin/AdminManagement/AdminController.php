<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\Admin;
use App\Http\Traits\DetailsCommonDataTrait;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\FileManagementTrait;
use App\Models\TempFile;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    use FileManagementTrait;
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('permission:admin-list|admin-delete|admin-status', ['only' => ['index', 'show']]);
        $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('permission:admin-status', ['only' => ['status']]);
    }

    use DetailsCommonDataTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admins = Admin::with(['creater_admin'])->get();
        if ($request->ajax()) {
            $admins = $admins->sortBy('sort_order');
            return DataTables::of($admins)
                ->editColumn('status', function ($admin) {
                    return "<span class='" . $admin->getStatusBadgeBg() . "'>" . $admin->getStatusBadgeTitle() . "</span>";
                })
                ->editColumn('created_at', function ($admin) {
                    return timeFormat($admin->created_at);
                })
                ->editColumn('created_by', function ($admin) {
                    return creater_name($admin->creater_admin);
                })
                ->editColumn('action', function ($admin) {
                    return view('backend.admin.includes.action_buttons', [
                        'menuItems' => [
                            [
                                'routeName' => 'javascript:void(0)',
                                'data-id' => $admin->id,
                                'className' => 'view',
                                'label' => 'Details',
                                'permissions' => ['admin-list', 'admin-delete', 'admin-status']
                            ],
                            [
                                'routeName' => 'am.admin.status',
                                'params' => [$admin->id],
                                'label' => $admin->getStatusBtnTitle(),
                                'permissions' => ['admin-status']
                            ],
                            [
                                'routeName' => 'am.admin.edit',
                                'params' => [$admin->id],
                                'label' => 'Edit',
                                'permissions' => ['admin-edit']
                            ],

                            [
                                'routeName' => 'am.admin.destroy',
                                'params' => [$admin->id],
                                'label' => 'Delete',
                                'delete' => true,
                                'permissions' => ['admin-delete']
                            ]
                        ],
                    ]);
                })
                ->rawColumns(['status', 'created_at', 'created_by', 'action'])
                ->make(true);
        }
        return view('backend.admin.admin_management.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['roles'] = Role::latest()->get();
        return view('backend.admin.admin_management.admin.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $req)
    {

        $admin = new Admin();


        $temp_file = TempFile::findOrFail($req->image);
        if ($temp_file) {
            $from_path = 'public/' . $temp_file->path . '/' . $temp_file->filename;
            $to_path = 'admins/' . str_replace(' ', '-', admin()->name) . '/' . time() . '/' . $temp_file->filename;
            Storage::move($from_path, 'public/' . $to_path);
            $admin->image = $to_path;
            Storage::deleteDirectory('public/' . $temp_file->path);
            $temp_file->forceDelete();
        }
        // $this->handleFileUpload($req, $admin, $admin->name, 'image', 'admins/');
        $admin->role_id = $req->role;
        $admin->name = $req->name;
        $admin->email = $req->email;
        $admin->password = $req->password;
        $admin->created_by = admin()->id;
        $admin->save();
        $admin->assignRole($admin->role->name);
        session()->flash('success', 'Admin created successfully!');
        return redirect()->route('am.admin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $data = Admin::with(['creater_admin', 'updater_admin'])->findOrFail($id);
        $this->AdminAuditColumnsData($data);
        $this->statusColumnData($data);
        $data->image = auth_storage_url($data->image);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data['admin'] = Admin::findOrFail($id);
        $data['roles'] = Role::latest()->get();
        return view('backend.admin.admin_management.admin.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminRequest $req, int $id)
    {
        $admin = Admin::findOrFail($id);
        $this->handleFileUpload($req, $admin, $admin->name, 'image', 'admins/');
        $admin->role_id = $req->role;
        $admin->name = $req->name;
        $admin->email = $req->email;
        if ($req->password) {
            $admin->password = $req->password;
        }
        $admin->updated_by = admin()->id;
        $admin->update();
        $admin->syncRoles($admin->role->name);
        session()->flash('success', 'Admin updated successfully!');
        return redirect()->route('am.admin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->deleted_by = admin()->id;
        $admin->save();
        $admin->delete();
        session()->flash('success', 'Admin deleted successfully!');
        return redirect()->route('am.admin.index');
    }

    public function status(int $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->status = !$admin->status;
        $admin->updated_by = admin()->id;
        $admin->update();
        session()->flash('success', 'Admin status updated successfully!');
        return redirect()->route('am.admin.index');
    }
}
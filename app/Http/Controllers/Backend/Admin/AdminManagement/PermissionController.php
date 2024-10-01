<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Traits\DetailsCommonDataTrait;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    use DetailsCommonDataTrait;
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
        $this->middleware('permission:permission-status', ['only' => ['status']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permissions = Permission::with('created_admin')->get();
        if ($request->ajax()) {
            $permissions = $permissions->sortBy('sort_order');
            return DataTables::of($permissions)
                ->editColumn('created_at', function ($permission) {
                    return timeFormat($permission->created_at);
                })
                ->editColumn('created_by', function ($permission) {
                    return creater_name($permission->creater_admin);
                })
                ->editColumn('action', function ($permission) {
                    return view('backend.admin.includes.action_buttons', [
                        'menuItems' => [
                            [
                                'routeName' => 'javascript:void(0)',
                                'data-id' => $permission->id,
                                'className' => 'view',
                                'label' => 'Details',
                                'permissions' => ['permission-list', 'permission-delete']
                            ],
                            [
                                'routeName' => 'am.permission.edit',
                                'params' => [$permission->id],
                                'label' => 'Edit',
                                'permissions' => ['permission-edit']
                            ],

                            [
                                'routeName' => 'am.permission.destroy',
                                'params' => [$permission->id],
                                'label' => 'Delete',
                                'delete' => true,
                                'permissions' => ['permission-delete']
                            ]
                        ],
                    ]);
                })
                ->rawColumns(['created_at', 'created_by', 'action'])
                ->make(true);
        }
        return view('backend.admin.admin_management.permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.admin_management.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $req)
    {
        $permission = new Permission();
        $permission->name = $req->name;
        $permission->prefix = $req->prefix;
        $permission->guard_name = 'admin';
        $permission->created_by = auth()->guard('admin')->user()->id;
        $permission->save();
        session()->flash('success', "$permission->name permission created successfully");
        return redirect()->route('am.permission.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $data = Permission::with(['created_admin', 'updated_admin'])->findOrFail($id);
        $this->AdminAuditColumnsData($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data['permission'] = Permission::findOrFail($id);
        return view('backend.admin.admin_management.permission.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->prefix = $request->prefix;
        $permission->guard_name = 'admin';
        $permission->updated_by = auth()->guard('admin')->user()->id;
        $permission->save();
        session()->flash('success', "$permission->name permission updated successfully");
        return redirect()->route('am.permission.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->deleted_by = auth()->guard('admin')->user()->id;
        $permission->save();
        $permission->delete();
        session()->flash('success', "$permission->name permission deleted successfully");
        return redirect()->route('am.permission.index');
    }
}
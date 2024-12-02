<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Traits\DetailsCommonDataTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    use DetailsCommonDataTrait;
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('permission:permission-list|permission-details|permission-delete', ['only' => ['index']]);
        $this->middleware('permission:permission-details', ['only' => ['show']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permissions = Permission::with('creater_admin')->get();
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
                    $menuItems = [
                        [
                            'routeName' => 'javascript:void(0)',
                            'data-id' => encrypt($permission->id),
                            'className' => 'view',
                            'label' => 'Details',
                            'permissions' => ['permission-list', 'permission-delete']
                        ],
                        [
                            'routeName' => 'am.permission.edit',
                            'params' => [encrypt($permission->id)],
                            'label' => 'Edit',
                            'permissions' => ['permission-edit']
                        ],

                        [
                            'routeName' => 'am.permission.destroy',
                            'params' => [encrypt($permission->id)],
                            'label' => 'Delete',
                            'delete' => true,
                            'permissions' => ['permission-delete']
                        ]
                    ];
                    return view('components.backend.admin.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['created_at', 'created_by', 'action'])
                ->make(true);
        }
        return view('backend.admin.admin_management.permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('backend.admin.admin_management.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $req): RedirectResponse
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
    public function show(string $id): JsonResponse
    {
        $data = Permission::with(['creater_admin', 'updater_admin'])->findOrFail(decrypt($id));
        $this->AdminAuditColumnsData($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['permission'] = Permission::findOrFail(decrypt($id));
        return view('backend.admin.admin_management.permission.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, string $id): RedirectResponse
    {
        $permission = Permission::findOrFail(decrypt($id));
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
    public function destroy(string $id): RedirectResponse
    {
        $permission = Permission::findOrFail(decrypt($id));
        $permission->deleted_by = auth()->guard('admin')->user()->id;
        $permission->delete();
        session()->flash('success', "$permission->name permission deleted successfully");
        return redirect()->route('am.permission.index');
    }
}
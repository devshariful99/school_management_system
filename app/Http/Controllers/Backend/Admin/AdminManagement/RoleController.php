<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\DetailsCommonDataTrait;

class RoleController extends Controller
{
    use DetailsCommonDataTrait;
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
        $this->middleware('permission:role-status', ['only' => ['status']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::with(['permissions', 'creater_admin'])->get();
        if ($request->ajax()) {
            $roles = $roles->sortBy('sort_order');
            return DataTables::of($roles)
                ->editColumn('created_at', function ($role) {
                    return timeFormat($role->created_at);
                })
                ->editColumn('created_by', function ($role) {
                    return creater_name($role->creater_admin);
                })
                ->editColumn('action', function ($role) {
                    return view('backend.admin.includes.action_buttons', [
                        'menuItems' => [
                            [
                                'routeName' => 'javascript:void(0)',
                                'data-id' => $role->id,
                                'className' => 'view',
                                'label' => 'Details',
                                'permissions' => ['role-list', 'role-delete']
                            ],
                            [
                                'routeName' => 'am.role.edit',
                                'params' => [$role->id],
                                'label' => 'Edit',
                                'permissions' => ['role-edit']
                            ],

                            [
                                'routeName' => 'am.role.destroy',
                                'params' => [$role->id],
                                'label' => 'Delete',
                                'delete' => true,
                                'permissions' => ['role-delete']
                            ]
                        ],
                    ]);
                })
                ->rawColumns(['created_at', 'created_by', 'action'])
                ->make(true);
        }
        return view('backend.admin.admin_management.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('prefix')->get();
        $data['groupedPermissions'] = $permissions->groupBy(function ($permission) {
            return $permission->prefix;
        });
        return view('backend.admin.admin_management.role.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'admin';
        $role->created_by = admin()->id;
        $role->save();

        $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
        $role->givePermissionTo($permissions);
        session()->flash('success', "$role->name role created successfully");
        return redirect()->route('am.role.index');
    }

    // /**
    //  * Display the specified resource.
    //  */
    public function show(string $id)
    {
        $data = Role::with(['permissions', 'creater_admin', 'updater_admin'])->findOrFail($id);
        $this->AdminAuditColumnsData($data);
        $data->permission_names = $data->permissions->pluck('name')->implode(' | ');
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data['role'] = Role::with('permissions')->findOrFail($id);
        $data['permissions'] = Permission::orderBy('prefix')->get();
        $data['groupedPermissions'] = $data['permissions']->groupBy(function ($permission) {
            return $permission->prefix;
        });
        return view('backend.admin.admin_management.role.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, int $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->updated_by = admin()->id;
        $role->save();
        $permissions = Permission::whereIn('id', $request->permissions ?? [])->pluck('name')->toArray();
        $role->syncPermissions($permissions);
        session()->flash('success', "$role->name role updated successfully");
        return redirect()->route('am.role.index');
    }


    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->deleted_by = auth()->guard('admin')->user()->id;
        $role->save();
        $role->delete();
        session()->flash('success', 'Role deleted successfully!');
        return redirect()->route('am.role.index');
    }
}
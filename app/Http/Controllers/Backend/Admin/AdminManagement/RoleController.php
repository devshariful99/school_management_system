<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        // $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'show']]);
        // $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:role-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:role-status', ['only' => ['status']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data['roles'] = Role::with(['permissions', 'created_admin'])->latest()->get();
            // ->each(function ($role) {
            //     $permissionNames = $role->permissions->pluck('name')->implode(' | ');
            //     $role->permissionNames = $permissionNames;
            //     return $role;
            // });
        return view('backend.admin.admin_management.role.index', $data);
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
        return redirect()->route('am.role.index')->withStatus(__('$role->name role created successfully'));
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     // $data = Role::with(['permissions', 'created_user', 'updated_user'])->findOrFail($id);
    //     // $this->simpleColumnData($data);
    //     // $data->permissionNames = $data->permissions->pluck('name')->implode(' | ');
    //     // return response()->json($data);
    // }

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
        $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
        $role->syncPermissions($permissions);
        return redirect()->route('am.role.index')->withStatus(__('$role->name role updated successfully'));
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //      $role = Role::findOrFail($id);
    //     $role->delete();

    //     flash()->addSuccess($role->name . ' role deleted successfully.');
    //     return redirect()->route('am.role.role_list');
    // }
}
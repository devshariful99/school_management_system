<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        // $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'show']]);
        // $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:permission-status', ['only' => ['status']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data['permissions'] = Permission::with(['created_admin'])->orderBy('prefix')->get();
        return view('backend.admin.admin_management.permission.index', $data);
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
        return redirect()->route('am.permission.index')->withStatus(__('$permission->name permission created successfully'));;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $data = Permission::findOrFail($id);
        $this->simpleColumnData($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $data['permission'] = Permission::findOrFail($id);
        $data['document'] = Documentation::where('module_key', 'permission')->first();
        return view('admin.admin_management.permission.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $permission = Permission::findOrFail($id);
        $permission->name = $req->name;
        $permission->prefix = $req->prefix;
        $permission->guard_name = 'admin';
        $permission->updated_by = admin()->id;
        $permission->update();
        flash()->addSuccess("$permission->name permission updated successfully");
        return redirect()->route('am.permission.permission_list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
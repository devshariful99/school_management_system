<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Traits\DetailsCommonDataTrait;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        // $this->middleware('permission:admin-list|admin-create|admin-edit|admin-delete', ['only' => ['index', 'show']]);
        // $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:admin-status', ['only' => ['status']]);
    }

    use DetailsCommonDataTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['admins'] = Admin::with('created_admin')->latest()->get();

        // $admins = Admin::latest()->get();
        // $students = Admin::latest()->get();
        // return view('backend.admin.admin_management.admin.index', compact('admins','students'));
        // return view('backend.admin.admin_management.admin.index',['admins'=> $admins, 'students'=>$students]);
        return view('backend.admin.admin_management.admin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.admin_management.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $req)
    {
        $admin = new Admin();
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = $req->name . '_' . time() . '.' . $image->getClientOriginalExtension();
            $folderName = 'admins/';
            $path = $image->storeAs($folderName, $imageName, 'public');
            $admin->image = $path;
        }
        $admin->name = $req->name;
        $admin->email = $req->email;
        $admin->password = $req->password;
        $admin->created_by = auth()->guard('admin')->user()->id;
        $admin->save();
        return redirect()->route('am.admin.index')->withStatus(__('Admin updated successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $data = Admin::with(['created_admin', 'updated_admin'])->findOrFail($id);
        $this->AdminAuditColumnsData($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data['admin'] = Admin::findOrFail($id);
        return view('backend.admin.admin_management.admin.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminRequest $req, int $id)
    {
        $admin = Admin::findOrFail($id);
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = $req->name . '_' . time() . '.' . $image->getClientOriginalExtension();
            $folderName = 'admins/';
            $path = $image->storeAs($folderName, $imageName, 'public');
            if (!empty($admin->image)) {
                $this->fileDelete($admin->image);
            }
            $admin->image = $path;
        }
        $admin->name = $req->name;
        $admin->email = $req->email;
        if ($req->password) {
            $admin->password = $req->password;
        }
        $admin->updated_by = auth()->guard('admin')->user()->id;
        $admin->update();
        return redirect()->route('am.admin.index')->withStatus(__('Admin updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->deleted_by = auth()->guard('admin')->user()->id;
        $admin->save();
        $admin->delete();
        return redirect()->route('am.admin.index')->withStatus(__('Admin deleted successfully'));
    }

    public function status(int $id)
    {
        $admin = Admin::findOrFail($id);
        $this->statusChange($admin);
        return redirect()->route('am.admin.index')->withStatus(__('Admin status updated successfully'));
    }
}
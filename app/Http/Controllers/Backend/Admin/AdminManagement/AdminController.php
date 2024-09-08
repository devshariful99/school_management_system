<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['admins'] = Admin::latest()->get();

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['admin'] = Admin::findOrFail($id);
        return view('backend.admin.admin_management.admin.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminRequest $req, string $id)
    {
        $admin = Admin::findOrFail($id);
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = $req->name . '_' . time() . '.' . $image->getClientOriginalExtension();
            $folderName = 'admins/';
            $path = $image->storeAs($folderName, $imageName, 'public');
            if (!empty($lf->image)) {
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
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();
        return redirect()->route('am.admin.index')->withStatus(__('Admin deleted successfully'));
    }
}

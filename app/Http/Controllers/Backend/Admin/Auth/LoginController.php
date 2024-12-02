<?php

namespace App\Http\Controllers\Backend\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function adminLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('backend.admin.login');
    }

    public function adminLoginCheck(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $check = Admin::where('email', $request->email)->first();
        if ($check) {
            if ($check->status == 1) {
                if (Auth::guard('admin')->attempt($credentials)) {
                    session()->flash('success', 'Welcome');
                    return redirect()->route('admin.dashboard');
                }
                session()->flash('error', 'Invalid credentials');
            } else {
                session()->flash('warning', 'Your account has been disabled. Please contact support.');
            }
        } else {
            session()->flash('error', 'Record not found');
        }
        return redirect()->route('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
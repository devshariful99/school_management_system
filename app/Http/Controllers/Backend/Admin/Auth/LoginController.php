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
                    // flash()->addSuccess('Welcome');
                    return redirect()->route('admin.dashboard');
                }
                // flash()->addError('Invalid credentials');
            } else {
                // flash()->addError('Your account has been disabled. Please contact support.');
            }
        } else {
            // flash()->addError('Admin Not Found');
        }
        return redirect()->route('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
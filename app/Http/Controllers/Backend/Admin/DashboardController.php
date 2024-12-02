<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('permission:dashboard', ['only' => ['dashboard']]);
    }
    public function dashboard(): View
    {
        return view('backend.admin.dashboard.dashboard');
    }
}
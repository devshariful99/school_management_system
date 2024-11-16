<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audit;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('permission:audit-list', ['only' => ['index']]);
        $this->middleware('permission:audit-details', ['only' => ['details']]);
    }
    public function index(Request $request)
    {
        $audits = Audit::latest()->get();
        if ($request->ajax()) {
            $audits = $audits->sortBy('sort_order');
            return DataTables::of($audits)
                ->editColumn('event', function ($audit) {
                    return ucfirst($audit->event);
                })
                ->editColumn('auditable_type', function ($audit) {
                    return getSubmitterType($audit->auditable_type);
                })
                ->editColumn('user_id', function ($audit) {
                    return $audit->user ? $audit->user->name : 'System';
                })
                ->editColumn('created_at', function ($audit) {
                    return timeFormat($audit->created_at);
                })
                ->editColumn('action', function ($audit) {
                    $menuItems = [
                        [
                            'routeName' => 'audit.details',
                            'params' => [encrypt($audit->id)],
                            'label' => 'Details',
                            'permissions' => ['audit-details']
                        ]

                    ];
                    return view('components.backend.admin.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['event', 'auditable_type', 'user_id', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.audits.index', compact('audits'));
    }

    public function details($id)
    {
        $audit = Audit::findOrFail(decrypt($id));
        return view('backend.admin.audits.details', compact('audit'));
    }
}

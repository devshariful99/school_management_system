<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\View\View;

class DocumentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('permission:documentation-list|documentation-delete|documentation-details', ['only' => ['index']]);
        $this->middleware('permission:documentation-details', ['only' => ['show']]);
        $this->middleware('permission:documentation-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:documentation-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:documentation-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $docs = Documentation::with(['creater_admin'])->get();
        if ($request->ajax()) {
            $docs = $docs->sortBy('sort_order');
            return DataTables::of($docs)
                ->editColumn('created_at', function ($doc) {
                    return timeFormat($doc->created_at);
                })
                ->editColumn('created_by', function ($doc) {
                    return creater_name($doc->creater_admin);
                })
                ->editColumn('action', function ($doc) {
                    $menuItems = [
                        [
                            'routeName' => 'javascript:void(0)',
                            'data-id' => encrypt($doc->id),
                            'className' => 'view',
                            'label' => 'Details',
                            'permissions' => ['documentation-details']
                        ],
                        [
                            'routeName' => 'doc.edit',
                            'params' => [encrypt($doc->id)],
                            'label' => 'Edit',
                            'permissions' => ['documentation-edit']
                        ],

                        [
                            'routeName' => 'doc.destroy',
                            'params' => [encrypt($doc->id)],
                            'label' => 'Delete',
                            'delete' => true,
                            'permissions' => ['documentation-delete']
                        ]

                    ];
                    return view('components.backend.admin.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['created_at', 'created_by', 'action'])
                ->make(true);
        }
        return view('backend.admin.documentation.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DocumentationRequest;
use App\Models\Documentation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Traits\DetailsCommonDataTrait;


class DocumentationController extends Controller
{
    use DetailsCommonDataTrait;
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
                            'routeName' => 'documentation.edit',
                            'params' => [encrypt($doc->id)],
                            'label' => 'Edit',
                            'permissions' => ['documentation-edit']
                        ],

                        [
                            'routeName' => 'documentation.destroy',
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
        return view('backend.admin.documentation.index', compact('docs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('backend.admin.documentation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentationRequest $req): RedirectResponse
    {
        $doc = new Documentation();
        $doc->title = $req->title;
        $doc->key = $req->key;
        $doc->type = $req->type;
        $doc->documentation = $req->documentation;
        $doc->created_by = admin()->id;
        $doc->save();
        session()->flash('success', 'Documentation created successfully!');
        return redirect()->route('documentation.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $data = Documentation::with(['creater_admin', 'updater_admin'])->findOrFail(decrypt($id));
        $data->documenatation = html_entity_decode($data->documentation);
        $this->AdminAuditColumnsData($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['doc'] = Documentation::findOrFail(decrypt($id));
        return view('backend.admin.documentation.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocumentationRequest $req, string $id): RedirectResponse
    {
        $doc = Documentation::findOrFail(decrypt($id));
        $doc->title = $req->title;
        $doc->key = $req->key;
        $doc->type = $req->type;
        $doc->documentation = $req->documentation;
        $doc->updated_by = admin()->id;
        $doc->update();
        session()->flash('success', 'Documentation updated successfully!');
        return redirect()->route('documentation.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $doc = Documentation::findOrFail(decrypt($id));
        $doc->deleted_by = admin()->id;
        $doc->delete();
        session()->flash('success', 'Documentation deleted successfully!');
        return redirect()->route('documentation.index');
    }
}
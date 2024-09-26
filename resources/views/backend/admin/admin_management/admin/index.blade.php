@extends('backend.admin.layouts.master', ['page_slug' => 'admin'])
@section('title', 'Admin List')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">Admin List</h4>
                    @include('backend.admin.includes.button', [
                            'routeName' => 'am.admin.create',
                            'label' => 'Add New',
                            'permissions'=>['admin-create'],
                        ])
                </div>
                <div class="card-body">
                    <table class="table table-responsive table-striped datatable">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Created Date') }}</th>
                                <th>{{ __('Created By') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td><span
                                            class="{{ $admin->getStatusBadgeBg() }}">{{ $admin->getStatusBadgeTitle() }}</span>
                                    </td>
                                    <td>{{ timeFormat($admin->created_at) }}</td>
                                    <td>{{ creater_name($admin->created_admin) }}</td>
                                    
                                    <td class="text-center">
                                          @include('backend.admin.includes.action_buttons', [
                                            'menuItems' => [
                                                [
                                                    'routeName' => 'javascript:void(0)',
                                                    'data-id' => $admin->id,
                                                    'className' => 'view',
                                                    'label' => 'Details',
                                                    'permissions'=>['admin-list','admin-delete','admin-status']
                                                ],
                                                [
                                                    'routeName' => 'am.admin.status',
                                                    'params' => [$admin->id],
                                                    'label' => $admin->getStatusBtnTitle(),
                                                    'permissions'=>['admin-status']
                                                ],
                                                [
                                                    'routeName' => 'am.admin.edit',
                                                    'params' => [$admin->id],
                                                    'label' => 'Edit',
                                                    'permissions'=>['admin-edit']
                                                ],
                                                
                                                [
                                                    'routeName' => 'am.admin.destroy',
                                                    'params' => [$admin->id],
                                                    'label' => 'Delete',
                                                    'delete' => true,
                                                    'permissions'=>['admin-delete']
                                                ]
                                            ],
                                        ])
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Admin Details Modal  --}}
    @include('backend.admin.includes.details_modal', ['modal_title' => 'Admin Details'])
@endsection
@push('js')
    {{-- Datatable Scripts --}}
    <script src="{{ asset('datatable/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            let table_columns = [
                //name and data, orderable, searchable
                ['name', true, true],
                ['email', true, true],
                ['status', true, true],
                ['created_at', false, false],
                ['created_by', true, true],
                ['action', false, false],
            ];
            const details = {
                table_columns: table_columns,
                main_class: '.datatable',
                displayLength: 10,
                main_route: "{{ route('am.admin.index') }}",
                order_route: "{{ route('update.sort.order') }}",
                export_columns: [0, 1, 2, 3, 4, 5],
                model: 'Admin',
            };
            initializeDataTable(details);
        })
    </script>
@endpush
@push('js')
    {{-- Show details scripts --}}
    <script src="{{ asset('modal/details_modal.js') }}"></script>
    <script>
        // Event listener for viewing details
        $(document).on("click", ".view", function() {
            let id = $(this).data("id");
            let route = "{{ route('am.admin.show', ['id']) }}";
            const detailsUrl = route.replace("id", id);
            const headers = [{
                    label: "Name",
                    key: "name"
                },
                {
                    label: "Email",
                    key: "email"
                },
                {
                    label: "Status",
                    key: "statusBadgeTitle",
                    badge: true,
                    badgeClass: 'statusBadgeBg',
                },
            ];
            fetchAndShowModal(detailsUrl, headers, "modal_wrap_id", "modal_id");
        });
    </script>
@endpush


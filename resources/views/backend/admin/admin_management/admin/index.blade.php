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
                    <table class="table table-responsive table-striped">
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
                            @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td><span
                                            class="{{ $admin->getStatusBadgeBg() }}">{{ $admin->getStatusBadgeTitle() }}</span>
                                    </td>
                                    <td>{{ timeFormat($admin->created_at) }}</td>
                                    <td>{{ c_user_name($admin->created_admin) }}</td>
                                    
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
                            @endforeach
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
    <script>
        $(document).ready(function() {
            $('.view').on('click', function() {
                let id = $(this).data('id');
                let url = ("{{ route('am.admin.show', ['id']) }}");
                let _url = url.replace('id', id);
                $.ajax({
                    url: _url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var result = `
                                <table class="table table-striped">
                                    <tr>
                                        <th class="text-nowrap">Name</th>
                                        <th>:</th>
                                        <td>${data.name}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Email</th>
                                        <th>:</th>
                                        <td>${data.email}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Status</th>
                                        <th>:</th>
                                        <td><span class="badge ${data.statusBadgeBg}">${data.statusBadgeTitle}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Created Date</th>
                                        <th>:</th>
                                        <td>${data.creating_time}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Created By</th>
                                        <th>:</th>
                                        <td>${data.created_by}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Updated Date</th>
                                        <th>:</th>
                                        <td>${data.updating_time}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Updated By</th>
                                        <th>:</th>
                                        <td>${data.updated_by}</td>
                                    </tr>
                                </table>
                                `;
                        $('.modal_data').html(result);
                        $('.view_modal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching admin data:', error);
                    }
                });
            });
        });
    </script>
@endpush

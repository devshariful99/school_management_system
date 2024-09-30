@extends('backend.admin.layouts.master', ['page_slug' => 'role'])
@section('title', 'Role List')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('alerts.success')
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">{{ __('Role List') }}</h4>
                    @include('backend.admin.includes.button', [
                        'routeName' => 'am.role.create',
                        'label' => 'Add New',
                        'permissions' => ['role-create'],
                    ])
                </div>
                <div class="card-body">
                    <table class="table table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Created Date') }}</th>
                                <th>{{ __('Created By') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ timeFormat($role->created_at) }}</td>
                                    <td>{{ creater_name($role->created_admin) }}</td>

                                    <td class="text-center">
                                        @include('backend.admin.includes.action_buttons', [
                                            'menuItems' => [
                                                [
                                                    'routeName' => 'javascript:void(0)',
                                                    'data-id' => $role->id,
                                                    'className' => 'view',
                                                    'label' => 'Details',
                                                    'permissions' => ['role-list', 'role-delete'],
                                                ],
                                                [
                                                    'routeName' => 'am.role.edit',
                                                    'params' => [$role->id],
                                                    'label' => 'Edit',
                                                    'permissions' => ['role-edit'],
                                                ],
                                        
                                                [
                                                    'routeName' => 'am.role.destroy',
                                                    'params' => [$role->id],
                                                    'label' => 'Delete',
                                                    'delete' => true,
                                                    'permissions' => ['role-delete'],
                                                ],
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
    @include('backend.admin.includes.details_modal', ['modal_title' => 'Role Details'])
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('.view').on('click', function() {
                let id = $(this).data('id');
                let url = ("{{ route('am.role.show', ['id']) }}");
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

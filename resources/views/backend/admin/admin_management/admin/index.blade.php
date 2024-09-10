@extends('backend.admin.layouts.master', ['page_slug' => 'admin'])
@section('title', 'Admin List')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('alerts.success')
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">Admin List</h4>
                    <a href="{{ route('am.admin.create') }}" class="btn btn-sm btn-primary">Add New</a>
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
                                <th>{{ __('Updated Date') }}</th>
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
                                    <td>{{ $admin->created_at != $admin->updated_at ? timeFormat($admin->updated_at) : 'NULL' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-rounded "
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="icon-options-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="javascript:void(0)" data-id="{{ $admin->id }}"
                                                        class="dropdown-item view">{{ __('Details') }}</a></li>
                                                <li><a href="{{ route('am.admin.edit', $admin->id) }}"
                                                        class="dropdown-item">{{ __('Edit') }}</a>
                                                </li>
                                                <li><a href="{{ route('am.admin.status', $admin->id) }}"
                                                        class="dropdown-item">{{ $admin->getStatusBtnTitle() }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        onclick="document.getElementById('delete-form').submit();">
                                                        {{ __('Delete') }}
                                                    </a>

                                                    <form id="delete-form"
                                                        action="{{ route('am.admin.destroy', $admin->id) }}" method="POST"
                                                        class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
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

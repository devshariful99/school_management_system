@extends('backend.admin.layouts.master', ['page_slug' => 'permission'])
@section('title', 'Permission List')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Permission List') }}</h4>
                    <a href="{{route('am.permission.create')}}" class="btn btn-sm btn-primary">Add New</a>
                </div>
                <div class="card-body">

                    <div class="">
                        <table class="table table-striped">
                            <thead class=" text-primary">
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Prefix') }}</th>
                                    <th>{{ __('Permisson') }}</th>
                                    <th>{{ __('Created Date') }}</th>
                                    <th>{{ __('Creadted By') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $permission)
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $permission->prefix }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ timeFormat($permission->created_at) }}</td>
                                        <td>{{ c_user_name($permission->created_user) }}</td>
                                         <td class="text-center">
                                        <div class="btn-group">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-rounded "
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="icon-options-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="javascript:void(0)" data-id="{{ $permission->id }}"
                                                        class="dropdown-item view">{{ __('Details') }}</a></li>
                                                <li><a href="{{ route('am.permission.edit', $permission->id) }}"
                                                        class="dropdown-item">{{ __('Edit') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        onclick="document.getElementById('delete-form').submit();">
                                                        {{ __('Delete') }}
                                                    </a>

                                                    <form id="delete-form"
                                                        action="{{ route('am.permission.destroy', $permission->id) }}" method="POST"
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
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Permission Details Modal  --}}
    <div class="modal view_modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Permission Details') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal_data">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.view', function() {
                let id = $(this).data('id');
                let url = ("{{ route('am.permission.show', ['id']) }}");
                let _url = url.replace('id', id);
                $.ajax({
                    url: _url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var result = `
                        <table class="table table-striped">
                            <tr>
                                <th class="text-nowrap">Prefix</th>
                                <th>:</th>
                                <td>${data.prefix}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Name</th>
                                <th>:</th>
                                <td>${data.name}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Guard Name</th>
                                <th>:</th>
                                <td>${data.guard_name}</td>
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
                        console.error('Error fetching user data:', error);
                    }
                });
            });
        });
    </script>
@endpush

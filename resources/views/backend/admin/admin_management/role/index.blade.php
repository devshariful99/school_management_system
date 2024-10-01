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
                    <table class="table table-responsive table-striped datatable">
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
    {{-- Datatable Scripts --}}
    <script src="{{ asset('datatable/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            let table_columns = [
                //name and data, orderable, searchable
                ['name', true, true],
                ['created_at', false, false],
                ['created_by', true, true],
                ['action', false, false],
            ];
            const details = {
                table_columns: table_columns,
                main_route: "{{ route('am.role.index') }}",
                order_route: "{{ route('update.sort.order') }}",
                export_columns: [0, 1, 2, 3],
                model: 'Role',
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
            let route = "{{ route('am.role.show', ['id']) }}";
            const detailsUrl = route.replace("id", id);
            const headers = [{
                    label: "Name",
                    key: "name"
                },
                {
                    label: "Guard Name",
                    key: "guard_name"
                },
                {
                    label: "Permissions",
                    key: "permission_names"
                },
            ];
            fetchAndShowModal(detailsUrl, headers, "#modal_data", "myModal");
        });
    </script>
@endpush

@extends('backend.admin.layouts.master', ['page_slug' => 'audits'])
@section('title', 'Software Audits')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">{{ __('Audit List') }}</h4>
                    {{-- <x-backend.admin.button :datas="[
                        'routeName' => 'am.admin.create',
                        'label' => 'Add New',
                        'permissions' => ['admin-create'],
                    ]" /> --}}
                </div>
                <div class="card-body">
                    <table class="table table-responsive table-striped datatable">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Event') }}</th>
                                <th>{{ __('Model') }}</th>
                                <th>{{ __('Changed By') }}</th>
                                <th>{{ __('IP Address') }}</th>
                                <th>{{ __('Modified At') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    {{-- Datatable Scripts --}}
    <script src="{{ asset('datatable/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            let table_columns = [
                //name and data, orderable, searchable
                ['event', true, true],
                ['auditable_type', true, true],
                ['user_id', true, true],
                ['ip_address', true, true],
                ['created_at', true, true],
                ['action', false, false],
            ];
            const details = {
                table_columns: table_columns,
                main_class: '.datatable',
                displayLength: 10,
                main_route: "{{ route('audit.index') }}",
                order_route: "{{ route('update.sort.order') }}",
                export_columns: [0, 1, 2, 3, 4, 5],
                model: 'Audit',
            };
            initializeDataTable(details);
        })
    </script>
@endpush

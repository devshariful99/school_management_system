@extends('backend.admin.layouts.master', ['page_slug' => 'temp_file'])
@section('title', 'Temporary File List')
@push('css')
    <link rel="stylesheet" href="{{ asset('custom_litebox/litebox.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">{{ __('Temporary File List') }}</h4>
                    @if (count($temps) > 1)
                        <x-backend.admin.button :datas="[
                            'routeName' => 'temp.destroy',
                            'label' => 'Delete All',
                            'params' => 'all',
                            'delete' => true,
                            'form_id' => 'temp_delete_form',
                            'permissions' => ['temp-delete'],
                        ]" />
                    @endif

                </div>
                <div class="card-body">
                    <table class="table table-responsive table-striped datatable">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('File') }}</th>
                                <th>{{ __('From') }}</th>
                                <th>{{ __('Download') }}</th>
                                <th>{{ __('Deleted Date') }}</th>
                                <th>{{ __('Deleted By') }}</th>
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
@endsection
@push('js')
    <script src="{{ asset('custom_litebox/litebox.js') }}"></script>
    {{-- Datatable Scripts --}}
    <script src="{{ asset('datatable/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            let table_columns = [
                //name and data, orderable, searchable
                ['path', false, false],
                ['from_type', true, true],
                ['filename', false, false],
                ['created_at', false, false],
                ['created_by', true, true],
                ['action', false, false],
            ];
            const details = {
                table_columns: table_columns,
                main_class: '.datatable',
                displayLength: 10,
                main_route: "{{ route('temp.index') }}",
                order_route: "{{ route('update.sort.order') }}",
                export_columns: [0, 1, 2, 3, 4, 5, 6],
                model: 'TempFile',
            };
            // initializeDataTable(details);

            initializeDataTable(details);
        })
    </script>
@endpush

@extends('backend.admin.layouts.master', ['page_slug' => 'audits'])
@section('title', 'Software Audits')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">{{ __('Audit List') }}</h4>
                    <x-backend.admin.button :datas="[
                        'routeName' => 'audit.index',
                        'label' => 'Back',
                        'permissions' => ['audit-index'],
                    ]" />
                </div>
                <div class="card-body">
                    <table class="table table-responsive table-striped">
                        <tbody>
                            <tr>
                                <td class="fw-bold">{{ __('Event') }}</td>
                                <td>:</td>
                                <td>{{ Str::ucfirst($audit->event) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('Changed By') }}</td>
                                <td>:</td>
                                <td>{{ $audit->user ? $audit->user->name : 'System' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('Model') }}</td>
                                <td>:</td>
                                <td>{{ getSubmitterType($audit->auditable_type) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('Old Values') }}</td>
                                <td>:</td>
                                <td>
                                    <table>
                                        @foreach ($audit->old_values as $key => $value)
                                            <tr>
                                                <td>{{ $key }} : </td>
                                                @if (($key == 'created_by' || $key == 'updated_by' || $key == 'deleted_by') && $value == null)
                                                    <td>{{ $audit->user ? $audit->user->name : 'System' }}</td>
                                                @else
                                                    <td>{{ $value }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('New Values') }}</td>
                                <td>:</td>
                                <td>
                                    <table>
                                        @foreach ($audit->new_values as $key => $value)
                                            <tr>
                                                <td>{{ $key }} : </td>
                                                @if (($key == 'created_by' || $key == 'updated_by' || $key == 'deleted_by') && $value == null)
                                                    <td>{{ $audit->user ? $audit->user->name : 'System' }}</td>
                                                @else
                                                    <td>{{ $value }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('URL') }}</td>
                                <td>:</td>
                                <td>{{ $audit->url }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('IP Address') }}</td>
                                <td>:</td>
                                <td>{{ $audit->ip_address }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('User Agent') }}</td>
                                <td>:</td>
                                <td>{{ $audit->user_agent }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('Tags') }}</td>
                                <td>:</td>
                                <td>{{ implode(',', $audit->getTags()) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('Modified At') }}</td>
                                <td>:</td>
                                <td>{{ $audit->created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

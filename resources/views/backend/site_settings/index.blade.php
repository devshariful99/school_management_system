@extends('backend.admin.layouts.master', ['page_slug' => 'site_setting'])
@section('title', 'Application Settings')
@push('css_link')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/css/bootstrap5-toggle.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="row site_settings px-3">
        <div class="tab col-md-2 p-md-3 pl-sm-3">
            <button id="tab1Btn" class="tablinks w-100 p-3 btn-success text-white"
                onclick="openTab(event, 'tab1')">{{ __('General Settings') }}</button>
            <button id="tab2Btn" class="tablinks p-3 w-100"
                onclick="openTab(event, 'tab2')">{{ __('Email Settings') }}</button>
            <button id="tab3Btn" class="tablinks p-3 w-100"
                onclick="openTab(event, 'tab3')">{{ __('Database Settings') }}</button>
            <button id="tab4Btn" class="tablinks p-3 w-100"
                onclick="openTab(event, 'tab4')">{{ __('SMS Settings') }}</button>
            <button id="tab5Btn" class="tablinks p-3 w-100"
                onclick="openTab(event, 'tab5')">{{ __('Email Templates') }}</button>
            <button id="tab6Btn" class="tablinks p-3 w-100"
                onclick="openTab(event, 'tab6')">{{ __('Notification Settings') }}</button>
        </div>
        <div class="col-md-10 p-0">
            <div id="tab1" class="tabcontent py-3">
                <x-backend.site-setting.general-setting />
            </div>
            <div id="tab2" class="tabcontent py-3 " style="display: none">
                <x-backend.site-setting.email-setting />
            </div>
            <div id="tab3" class="tabcontent py-3 " style="display: none">
                <x-backend.site-setting.database-setting />
            </div>
            <div id="tab4" class="tabcontent py-3 " style="display: none">
                <x-backend.site-setting.sms-setting />
            </div>
            <div id="tab5" class="tabcontent py-3 " style="display: none">
                <x-backend.site-setting.email-template-setting />
            </div>
            <div id="tab6" class="tabcontent py-3 " style="display: none">
                <x-backend.site-setting.notification-setting />
            </div>
        </div>
    </div>
@endsection
@push('js_link')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/js/bootstrap5-toggle.ecmas.min.js"></script>
@endpush
@push('js')
    <script src="{{ asset('backend/admin/js/site_settings.js') }}"></script>
@endpush

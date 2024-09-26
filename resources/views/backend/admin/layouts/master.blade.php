<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin -Dashboard')</title>
     <!-- Favicon -->
    <link rel="icon" href="{{ asset('backend/admin/assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <!-- Fonts and icons -->
    <script src="{{ asset('backend/admin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('backend/admin/assets/css/fonts.min.css') }}"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <link
        href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.1/r-3.0.3/rr-1.5.0/datatables.min.css"
        rel="stylesheet">
    @stack('css_links')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/kaiadmin.min.css') }}" />
    {{-- Custom CSS   --}}
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/custom.css') }}">
    @stack('css')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif

            @if (session('warning'))
                showAlert('warning', '{{ session('warning') }}');
            @endif
        });
    </script>
    <style>
        .dt-container .dt-buttons {
            display: inline-block;
        }

        .dt-container .dt-search {
            float: right;
        }

        .dt-container .dt-info {
            margin-top: 20px;
            display: inline-block;
        }

        .dt-container .dt-paging {
            margin-top: 10px;
            float: right;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('backend.admin.partials.sidebar')
        <!-- End Sidebar -->

        <div class="main-panel">
            <!-- Header -->
            @include('backend.admin.partials.header')
            <!-- End Header -->

            <div class="container">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            @include('backend.admin.partials.footer')
            <!-- End Footer -->
        </div>
    </div>
</body>


<!--   Core JS Files   -->
<script src="{{ asset('backend/admin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
<!-- Kaiadmin JS -->
<script src="{{ asset('backend/admin/assets/js/kaiadmin.min.js') }}"></script>
@stack('js_links')
<script src="{{ asset('backend/admin/js/custom.js') }}"></script>
@stack('js')

</html>

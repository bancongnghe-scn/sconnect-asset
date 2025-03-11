<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title}}</title>
    <link rel="icon" type="image/png" href="/images/fav-sc-icon.png"/>

    <!-- Scripts -->
    <script src="/js/const.js"></script>
    <script src='{{ asset('/js/jquery.js') }}'></script>
    <script src='{{ asset('/js/select2.full.js') }}'></script>
    <script src='{{ asset('/js/adminlte.js') }}'></script>
    <script src='{{ asset('/js/summernote-lite.js') }}'></script>

    @vite([
        'resources/css/app.css',
        'resources/sass/app.scss',
        'resources/css/custom.css',
        'resources/js/app.js',
    ])

    @yield('css')
</head>
<body>
    <div class="modal fade show" style="display: block;"
         x-data="{loading: false, permission: {{\Auth::user()->getAllPermissions()->pluck('name')}}}">
        <div class="modal-dialog modal-fullscreen" @yield('x-data')>
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center tw-gap-x-3">
                        <h4 class="modal-title">{{$title}}</h4>
                        @yield('title_other')
                    </div>
                    <div class="d-flex tw-gap-x-2">
                        @yield('btn-header')
                    </div>
                </div>
                <div class="modal-body">
                    @yield('content')
                </div>
            </div>
        </div>
        <div x-show="loading" class="tw-fixed tw-left-1/2 tw-top-20">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="tw-fixed tw-inset-0 tw-bg-black tw-bg-opacity-20 z-40"></div>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <div class="toast-container position-fixed tw-top-14 tw-right-2"></div>
    </div>
    @yield('js')
</body>
</html>


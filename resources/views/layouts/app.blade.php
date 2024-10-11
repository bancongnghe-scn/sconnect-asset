<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title}}</title>
    <link rel="icon" type="image/png" href="/images/fav-sc-icon.png" />

    <!-- Scripts -->
    <script src='/js/jquery.js'></script>
    <script src='/js/select2.full.js'></script>
    <script src='/js/adminlte.js'></script>

    @vite([
        'resources/css/app.css',
        'resources/sass/app.scss',
        'resources/css/custom.css',
        'resources/js/bootstrap.js',
        'resources/js/app.js',
    ])

    @yield('css')
</head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            @include('layouts.header')

            @include('layouts.sidebar')

            <div class="content-wrapper" x-data="{loading: false}">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 color-sc">{{$title}}</h1>
                            </div>
                            @if($title !== "Trang chủ")
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="/home"><i class="fa-solid fa-house"></i></a></li>
                                        <li class="breadcrumb-item active">{{$title}}</li>
                                    </ol>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </section>

                <div x-show="loading" class="tw-fixed tw-left-1/2 tw-top-20">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="tw-fixed tw-inset-0 tw-bg-black tw-bg-opacity-20 z-40"></div>
                </div>
            </div>

            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>

            <div class="toast-container position-fixed top-0 tw-right-2"></div>
        </div>

        @yield('js')
    </body>
</html>


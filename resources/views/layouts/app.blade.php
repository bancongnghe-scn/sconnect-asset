<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title}}</title>

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('css')
</head>
<body>
@include('layouts.header')

@include('layouts.sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>{{$title}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            @yield('content')
        </div>
    </section>
</main><!-- End #main -->

@include('layouts.footer')
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

{{--<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>--}}
{{--<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>--}}
{{--<script src="assets/vendor/chart.js/chart.umd.js"></script>--}}
{{--<script src="assets/vendor/echarts/echarts.min.js"></script>--}}
{{--<script src="assets/vendor/quill/quill.js"></script>--}}
{{--<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>--}}
{{--<script src="assets/vendor/tinymce/tinymce.min.js"></script>--}}
{{--<script src="assets/vendor/php-email-form/validate.js"></script>--}}
{{--<script src="assets/js/main.js"></script>--}}

@yield('js')
</body>
</html>

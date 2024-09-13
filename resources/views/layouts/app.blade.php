<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title}}</title>

    <!-- Favicons -->
    <link href='/assets/img/favicon.png' rel="icon">
    <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Template Main CSS File -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <!-- Scripts -->
    @vite([
        'node_modules/select2/dist/css/select2.min.css',
        'resources/sass/app.scss',
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/select2/dist/js/select2.min.js',
        'resources/js/app.js',
    ])

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

@yield('js')
</body>
</html>

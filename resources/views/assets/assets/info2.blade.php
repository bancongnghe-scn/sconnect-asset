<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chi tiết</title>
    <link rel="icon" type="image/png" href="/images/fav-sc-icon.png"/>

    <script src="/js/const.js"></script>
    <script src='{{ asset('/js/jquery.js') }}'></script>
    <script src='{{ asset('/js/select2.full.js') }}'></script>
    @vite([
           'resources/css/app.css',
           'resources/sass/app.scss',
           'resources/css/custom.css',
           'resources/js/app.js',
    ])
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div x-data="{id: {{$id}}}" class="container">
    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
</div>

</body>
</html>

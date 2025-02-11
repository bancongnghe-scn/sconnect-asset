@extends('layouts.app',[
    'title' => 'Báo cáo'
])

@section('content')
    @yield('content')
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('js')
@yield('js')
@endsection
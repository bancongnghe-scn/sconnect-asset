@extends('layouts.app_v2',[
    'title' => 'Chi tiết đơn hàng'
])

@section('btn-header')
    <a class="btn btn-sc" :href="`/api/order/export/{{$id}}`" download>
        <i class="fa-solid fa-file-export"></i>Gửi NCC
    </a>
    <a class="btn btn-warning" href="/order/list">Quay lại</a>
@endsection

@section('content')
    <div>
        <div class="d-flex tw-gap-x-4 h-100" x-data="order_detail({{$id}})">
            <div class="card col-10 mh-100 overflow-y-auto custom-scroll">
                <div class="card-body">
                    @include('assets.order.order_info')
                </div>
            </div>
            <div class="card col-2">
                @include('assets.order.history_comment')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/order/order_detail.js',
        'resources/js/assets/api/order/apiOrder.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/apiShoppingAssetOrder.js',
    ])
@endsection

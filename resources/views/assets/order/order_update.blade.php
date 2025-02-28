@extends('layouts.app_v2',[
    'title' => 'Cập nhật đơn hàng'
])

@section('x-data')
    x-data="order_update({{$id}})"
@endsection

@section('btn-header')
    <button @click="update()" type="button" class="btn btn-primary">Lưu</button>
    <a class="btn btn-sc" :href="`/api/order/export/{{$id}}`" download>
        <i class="fa-solid fa-file-export"></i>Gửi NCC
    </a>
    <a class="btn btn-warning" href="/order/list">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-4 h-100">
        <div class="card col-10 mh-100 overflow-y-auto custom-scroll">
            <div class="card-body">
                <div class="mb-3">
                    @include('assets.order.order_info_general', ['disabled' => false])
                </div>

                <div class="mb-3">
                    @include('assets.order.shopping_asset_info')
                </div>

                <hr>

                @include('assets.order.costs_other', ['disabled' => false])
            </div>
        </div>
        <div class="card col-2">
            @include('assets.order.history_comment')
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/order/order_update.js',
        'resources/js/assets/api/order/apiOrder.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/apiShoppingAssetOrder.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiOrganization.js',
    ])
@endsection

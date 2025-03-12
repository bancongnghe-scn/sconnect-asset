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
    <a class="btn btn-warning" href="/order/list?tab_status={{ request('status') ?? null}}">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-3 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll">
            <div class="mb-3">
                @include('assets.order.order_info_general', ['action' => 'update'])
            </div>

            <div class="mb-3">
                @include('assets.order.shopping_asset_info', ['action' => 'update'])
            </div>

            <hr>

            @include('assets.order.costs_other', ['action' => 'update'])
        </div>
        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_ORDER'])
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
        'resources/js/assets/api/apiIndustry.js',
    ])
@endsection

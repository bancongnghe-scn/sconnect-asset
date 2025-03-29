@extends('layouts.app_v2',[
    'title' => 'Đề xuất mua sắm phát sinh'
])

@section('x-data')
    x-data="shopping_arise_organization({{$id}})"
@endsection


@section('title_other')
    <div class="tw-h-fit">
        @include('component.status.status_shopping_arise', [
          'status' => 'data.status'
        ])
    </div>
@endsection

@section('btn-header')
    <button @click="sendShoppingArise()" type="button" class="btn btn-primary">Gửi đề xuất</button>
    <button @click="updateShoppingArise()" type="button" class="btn btn-sc">Lưu</button>
    <a class="btn btn-warning" href="/shopping-arise/list">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-3 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll">
            @include('assets.shopping_arise.organization.shopping_arise_info')
        </div>
        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_ORDER'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_arise/organization/shopping_arise_update.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiOrganization.js',
        'resources/js/assets/api/apiShoppingArise.js',
        'resources/js/app/api/apiJob.js',
    ])
@endsection

@extends('layouts.app',[
    'title' => 'Đề xuất mua sắm phát sinh'
])

@section('content')
    <div x-data="shopping_arise_list">
        <div>
            <div class="card card-body">
                <div>
                    @include('assets.shopping_arise.organization.filters')
                </div>

                <div class="mt-3">
                    @include('assets.shopping_arise.organization.table')
                </div>
            </div>
        </div>

        {{--modal--}}
        @include('assets.shopping_arise.organization.modal_insert')
        <div
            x-data="{
                modalId: 'modalConfirmDelete',
                contentBody: 'Bạn có chắc chắn muốn xóa đề xuất mua sắm này không ?'
            }"
            @ok="deleteShoppingArise"
        >
            @include('common.modal-confirm')
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_arise/organization/shopping_arise_list.js',
        'resources/js/assets/api/apiShoppingArise.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiJob.js'
    ])
@endsection

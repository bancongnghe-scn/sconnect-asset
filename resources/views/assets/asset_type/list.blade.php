@extends('layouts.app',[
    'title' => 'Loại tài sản'
])

@section('content')
    <div x-data="assetType">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handShowModalUI('create')">
                Thêm mới
            </button>
            <button type="button" class="btn tw-bg-red-600 tw-text-white" @click="confirmDeleteMultiple" :disabled="window.checkDisableSelectRow">
                Xóa chọn
            </button>
        </div>

        <div>
            @include('assets.asset_type.filters')
        </div>

        <div
            @edit="handShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('common.table')
        </div>

        {{-- modal--}}
        <div>
            <div>
                @include('assets.asset_type.modalUI')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa loại tài sản này không ?'
            }"
                @ok="remove"
            >
                @include('common.modal-confirm')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách loại tài sản này không ?'
            }"
                @ok="deleteMultiple"
            >
                @include('common.modal-confirm')
            </div>
        </div>
    </div>
@endsection

@section('js')

    @vite([
        'resources/js/assets/assetType.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/assets/api/apiAssetTypeGroup.js',
    ])
@endsection

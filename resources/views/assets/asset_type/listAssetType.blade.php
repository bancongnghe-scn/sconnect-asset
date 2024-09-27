@extends('layouts.app',[
    'title' => 'Loại tài sản'
])

@section('content')
    <div x-data="assetType">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-primary" @click="handShowModalAssetTypeUI('create')">
                Thêm mới
            </button>
            <button type="button" class="btn tw-bg-red-600 tw-text-white" @click="confirmDeleteMultiple">
                Xóa chọn
            </button>
        </div>

        <div>
            @include('assets.asset_type.filterAssetType')
        </div>

        <div
            @edit="handShowModalAssetTypeUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('common.table')
        </div>

        {{-- modal--}}
        <div>
            <div
                @save-asset-type="handleAssetTypeUI">
                @include('assets.asset_type.modalAssetTypeUI')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa loại tài sản này không ?'
            }"
                @ok="removeAssetType"
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
        'resources/js/assets/listAssetType.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/assets/api/apiAssetTypeGroup.js',
    ])
@endsection

@extends('layouts.app',[
    'title' => 'Nhóm tài sản'
])

@section('content')
    <div x-data="typeGroup">
        <div class="tw-mb-3 d-flex gap-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handleShowModal('create')">
                Thêm mới
            </button>
            <button type="button" class="btn tw-bg-red-600 tw-text-white" @click="confirmRemoveMultiple">
                Xóa chọn
            </button>
        </div>

        <div>
            @include('assets.asset_type_groups.filters')
        </div>

        <div
            @edit="handleShowModal('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changLimit"
        >
            @include('common.table')
        </div>


        <div>
            @include('assets.asset_type_groups.modalUI')

            <div
                x-data="{
                    modalId: idModalConfirmDelete,
                    contentBody: 'Bạn có chắc chắn muốn xóa nhóm tài sản này không ?'
                }"
                @ok="remove"
            >
                @include('common.modal-confirm')
            </div>

            <div
                x-data="{
                    modalId: idModalConfirmDeleteMultiple,
                    contentBody: 'Bạn có chắc chắn muốn xóa danh sách nhóm tài sản này không ?'
                }"
                @ok="removeMultiple"
            >
                @include('common.modal-confirm')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/assetTypeGroup.js',
        'resources/js/assets/api/apiAssetTypeGroup.js',
    ])
@endsection

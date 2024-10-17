@extends('layouts.app',[
    'title' => 'Quản lý hợp đồng'
])

@section('content')
    <div x-data="contract">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
            <button type="button" class="btn tw-bg-red-600 tw-text-white" @click="confirmRemoveMultiple">
                Xóa chọn
            </button>
        </div>

        <div>
            @include('assets.contract.filters')
        </div>

        <div
            @edit="handleShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @view="handleShowModalInfo($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('assets.contract.table')
        </div>

        {{-- modal--}}
        @include('assets.contract.modalUI')

        @include('assets.contract.modalInfo')

        <div
            x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa hợp đồng này không ?'
            }"
            @ok="removeContract"
        >
            @include('common.modal-confirm')
        </div>

        <div
            x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách hợp đồng này không ?'
            }"
            @ok="removeMultiple"
        >
            @include('common.modal-confirm')
        </div>

    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/contract.js',
        'resources/js/assets/api/apiContract.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/assets/api/apiUser.js',
    ])
@endsection

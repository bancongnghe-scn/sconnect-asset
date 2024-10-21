@extends('layouts.app',[
    'title' => 'Phụ lục hợp đồng'
])

@section('content')
    <div x-data="appendix">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
            <button type="button" class="btn tw-bg-red-600 tw-text-white"  @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                Xóa chọn
            </button>
        </div>

        <div>
            @include('assets.contract_appendix.filter')
        </div>

        <div
            @edit="handleShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @view="handleShowModalInfo($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('assets.contract_appendix.table')
        </div>

        {{--  modal--}}

        @include('assets.contract_appendix.modalUI')
        @include('assets.contract_appendix.modalInfo')

        <div
            x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa phụ lục hợp đồng này không ?'
            }"
            @ok="remove"
        >
            @include('common.modal-confirm')
        </div>

        <div
            x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách phụ lục hợp đồng này không ?'
            }"
            @ok="removeMultiple"
        >
            @include('common.modal-confirm')
        </div>
    </div>

@endsection

@section('js')
    @vite([
        'resources/js/assets/appendix.js',
        'resources/js/assets/api/apiContract.js',
        'resources/js/assets/api/apiAppendix.js',
        'resources/js/assets/api/apiUser.js',
    ])
@endsection

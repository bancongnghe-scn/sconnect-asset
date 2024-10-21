@extends('layouts.app',[
    'title' => 'Ngành hàng'
])

@section('content')
    <div x-data="industry">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
            <button type="button" class="btn tw-bg-red-600 tw-text-white"  @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                Xóa chọn
            </button>
        </div>

        <div>
            @include('assets.industry.filters')
        </div>

        <div
            @edit="handleShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('common.table')
        </div>

        {{-- modal--}}
        <div>
            <div
                @save-industry="handleUI">
                @include('assets.industry.modalUI')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa ngành hàng này không ?'
            }"
                @ok="remove"
            >
                @include('common.modal-confirm')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách ngành hàng này không ?'
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
        'resources/js/assets/industry.js',
        'resources/js/assets/api/apiIndustry.js',
    ])
@endsection

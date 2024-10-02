@extends('layouts.app',[
    'title' => 'Quản lý hợp đồng'
])

@section('content')
    <div x-data="contract">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-primary" @click="handleShowModalContractUI('create')">
                Thêm mới
            </button>
        </div>

        <div>
            @include('assets.contract.filterContract')
        </div>

        <div
            @edit="handShowModalContractUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('assets.contract.tableContract')
        </div>

        {{-- modal--}}
        <div>
            <div
                @include('assets.contract.modalContractUI')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa hợp đồng này không ?'
            }"
                @ok="removeContract"
            >
                @include('common.modal-confirm')
            </div>
        </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/listContract.js',
        'resources/js/assets/api/apiContract.js',
    ])
@endsection

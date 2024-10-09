@extends('layouts.app',[
    'title' => 'Vai trò'
])

@section('content')
    <div x-data="role">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
        </div>

        <div>
            @include('rbac.role.filter')
        </div>

        <div
            @edit="handleShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @view="handleShowModalInfo($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('common.table')
        </div>

        {{--  modal--}}
        <div>
            <div
            @include('rbac.role.modalUI')
        </div>

        <div
            x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa vai trò này không ?'
            }"
            @ok="remove"
        >
            @include('common.modal-confirm')
        </div>
    </div>

@endsection

@section('js')
    @vite([
        'resources/js/rbac/role.js',
        'resources/js/rbac/api/apiRole.js',
        'resources/js/rbac/api/apiPermission.js',
    ])
@endsection

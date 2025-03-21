@extends('layouts.app',[
    'title' => 'Quyền ứng dụng'
])

@section('content')
    <div x-data="permissions">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
        </div>

        <div>
            @include('rbac.permission.filter')
        </div>

        <div
            @edit="handleShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @view="handleShowModalInfo($event.detail.id)"
        >
            @include('common.table')
        </div>

        {{--  modal--}}
        @include('rbac.permission.modalUI')

        <div
            x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa quyền này không ?'
            }"
            @ok="remove"
        >
            @include('common.modal-confirm')
        </div>
    </div>

@endsection

@section('js')
    @vite([
        'resources/js/rbac/permission.js',
        'resources/js/rbac/api/apiPermission.js',
        'resources/js/rbac/api/apiRole.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection

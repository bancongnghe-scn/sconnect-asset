@extends('layouts.app',[
    'title' => 'Menu'
])

@section('content')
    <div x-data="menu">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
        </div>

        <div>
            @include('rbac.menu.filter')
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
            @include('rbac.menu.modalUI')
        </div>

        <div
            x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn menu này không ?'
            }"
            @ok="remove"
        >
            @include('common.modal-confirm')
        </div>
    </div>

@endsection

@section('js')
    @vite([
        'resources/js/rbac/menu.js',
        'resources/js/rbac/api/apiRole.js',
        'resources/js/rbac/api/apiMenu.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection

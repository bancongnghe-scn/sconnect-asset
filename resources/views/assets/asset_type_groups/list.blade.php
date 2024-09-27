@extends('layouts.app',[
    'title' => 'Nhóm tài sản'
])

@section('content')
    <div x-data="typeGroup">
        <div class="tw-mb-3 d-flex tw-justify-end">
            <button type="button" class="btn btn-primary" @click="handleShowModalCreateOrUpdate('create')">
                Thêm mới
            </button>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex flex-row align-items-end tw-gap-x-4">
                        <div class="form-group col-4">
                            <label class="tw-font-bold">Tên nhóm tài sản</label>
                            <input type="text" class="form-control" x-model="filters.name">
                        </div>
                        <div class="">
                            <button @click="searchTypeGroup" type="button" class="btn btn-block btn-primary">Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            @edit="handleShowModalCreateOrUpdate('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changLimit"
        >
            @include('common.table')
        </div>

        {{-- modal--}}
        <div
            @save-type-group="handleCreateOrUpdate">
            @include('assets.asset_type_groups.modalCreateTypeGroup')
        </div>

        <div
            x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa nhóm tài sản này không ?'
            }"
            @ok="removeTypeGroup"
        >
            @include('common.modal-confirm')
        </div>
    </div>
@endsection

@section('js')
    @vite('resources/js/assets/listAssetTypeGroup.js')
@endsection

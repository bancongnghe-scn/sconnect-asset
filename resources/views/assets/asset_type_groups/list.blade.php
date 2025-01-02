@extends('layouts.app',[
    'title' => 'Nhóm tài sản'
])

@section('content')
    <div x-data="typeGroup">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tw-mt-8">
                            @include('assets.asset_type_groups.filters')
                        </div>

                        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
                            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModal('create')">
                                <span>+ Thêm</span>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
                            </button>
                        </div>

                        <div
                            @edit="handleShowModal('update', $event.detail.id)"
                            @remove="confirmRemove($event.detail.id)"
                            @change-page.window="changePage($event.detail.page)"
                            @change-limit.window="changLimit"
                        >
                            @include('common.table')
                        </div>
                    </div>
                </div>
            </div>
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

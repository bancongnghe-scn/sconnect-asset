@extends('layouts.app',[
    'title' => 'Loại tài sản'
])

@section('content')
    <div x-data="assetType">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tw-mt-8">
                            @include('assets.asset_type.filters')
                        </div>

                        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
                            <button class="btn btn-sc btn-sm px-3" type="button" @click="handShowModalUI('create')">
                                <span>+ Thêm</span>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmDeleteMultiple" :disabled="window.checkDisableSelectRow">
                                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
                            </button>
                        </div>

                        <div
                            @edit="handShowModalUI('update', $event.detail.id)"
                            @remove="confirmRemove($event.detail.id)"
                            @change-page.window="changePage($event.detail.page)"
                            @change-limit.window="changeLimit"
                        >
                            @include('common.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- modal--}}
        <div>
            <div>
                @include('assets.asset_type.modalUI')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa loại tài sản này không ?'
            }"
                @ok="remove"
            >
                @include('common.modal-confirm')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách loại tài sản này không ?'
            }"
                @ok="deleteMultiple"
            >
                @include('common.modal-confirm')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/assetType.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/assets/api/apiAssetTypeGroup.js',
    ])
@endsection

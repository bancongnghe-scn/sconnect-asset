@extends('layouts.app',[
    'title' => 'Nhà cung cấp'
])

@section('content')
    <div x-data="supplier">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tw-mt-8">
                            @include('assets.supplier.filters')
                        </div>

                        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
                            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModalUI('create')">
                                <span>+ Thêm</span>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
                            </button>
                        </div>

                        <div
                            @edit="handleShowModalUI('update', $event.detail.id)"
                            @remove="confirmRemove($event.detail.id)"
                            @change-page.window="changePage($event.detail.page)"
                            @change-limit.window="changeLimit"
                        >
                            @include('assets.supplier.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- modal--}}
        <div>
            <div>
                @include('assets.supplier.modalUI')
            </div>

            <div
                x-data="{
                        modalId: idModalConfirmDelete,
                        contentBody: 'Bạn có chắc chắn muốn xóa nhà cung cấp này không ?'
                    }"
                @ok="remove"
            >
                @include('common.modal-confirm')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách nhà cung cấp này không ?'
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
        'resources/js/assets/supplier.js',
        'resources/js/assets/api/apiIndustry.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/assets/api/apiAssetType.js',
    ])
@endsection


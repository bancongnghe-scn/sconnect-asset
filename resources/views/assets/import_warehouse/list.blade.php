@extends('layouts.app',[
    'title' => 'Quản lý phiếu nhập kho'
])

@section('content')
    <div x-data="import_warehouse_list">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
            <a href="/api/import-warehouse/export" download>
                <button type="button" class="btn btn-primary">
                    Xuất Excel
                </button>
            </a>
        </div>

        <div>
            @include('assets.import_warehouse.filters')
        </div>

        <div
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('assets.import_warehouse.table')
        </div>

        {{-- modal--}}
        <div>
            <div>
                @include('assets.import_warehouse.modalUI')
            </div>

            <div
                x-data="{
                        modalId: 'modalConfirmDelete',
                        contentBody: 'Bạn có chắc chắn muốn xóa phiếu nhập kho này không ?'
                    }"
                @ok="remove"
            >
                @include('common.modal-confirm')
            </div>

            <div
                x-data="{
                        modalId: 'modalConfirmComplete',
                        contentBody: 'Bạn có chắc chắn muốn hoàn thành phiếu nhập kho này không ?'
                    }"
                @ok="completeImportWarehouse"
            >
                @include('common.modal-confirm')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/import_warehouse.js',
        'resources/js/assets/api/apiImportWarehouse.js',
        'resources/js/assets/api/order/apiOrder.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection


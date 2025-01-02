@extends('layouts.app',[
    'title' => 'Quản lý phiếu nhập kho'
])

@section('content')
    <div x-data="import_warehouse_list">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tw-mt-8">
                            @include('assets.import_warehouse.filters')
                        </div>

                        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
                            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModalUI('create')">
                                <span>+ Thêm</span>
                            </button>
                            <a href="/api/import-warehouse/export" download>
                                <button type="button" class="btn btn-sm btn-outline-success">
                                    <i class="fa-solid fa-file-export"></i>
                                    Xuất Excel
                                </button>
                            </a>
                        </div>

                        <div
                            @change-page.window="changePage($event.detail.page)"
                            @change-limit.window="changeLimit"
                        >
                            @include('assets.import_warehouse.table')
                        </div>
                    </div>
                </div>
            </div>
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


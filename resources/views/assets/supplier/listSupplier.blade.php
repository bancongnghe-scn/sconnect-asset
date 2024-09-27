@extends('layouts.app',[
    'title' => 'Nhà cung cấp'
])

@section('content')
    <div x-data="supplier">
        <div class="tw-mb-3 d-flex tw-justify-end">
            <button type="button" class="btn btn-primary" @click="handShowModalSupplierUI('create')">
                Thêm mới
            </button>
        </div>

        <div>
            @include('assets.supplier.filterSupplier')
        </div>

        <div
            @edit="handShowModalSupplierUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('assets.supplier.tableSupplier')
        </div>

        {{-- modal--}}
        <div>
            <div
                @save-supplier="handleSupplierUI">
                @include('assets.supplier.modalSupplierUI')
            </div>

            <div
                x-data="{
                        modalId: idModalConfirmDelete,
                        contentBody: 'Bạn có chắc chắn muốn xóa nhà cung cấp này không ?'
                    }"
                @ok="removeSupplier"
            >
                @include('common.modal-confirm')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/listSupplier.js',
        'resources/js/assets/api/apiIndustry.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/helpers.js',
    ])
@endsection


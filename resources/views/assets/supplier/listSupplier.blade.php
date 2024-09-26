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
    <script src="/js/asset/api/apiSupplier.js"></script>
    <script src="/js/asset/api/apiIndustry.js"></script>
    <script src="/js/asset/api/apiAssetType.js"></script>
    <script src="/js/asset/listSupplier.js"></script>
    <script src="/js/asset/helpers.js"></script>
@endsection


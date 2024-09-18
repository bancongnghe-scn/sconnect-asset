@extends('layouts.app',[
    'title' => 'Loại tài sản'
])

@section('content')
    <div x-data="assetType">
        <div class="tw-mb-3 d-flex tw-justify-end">
            <button type="button" class="btn btn-primary" @click="handShowModalAssetTypeUI('create')">
                Thêm mới
            </button>
        </div>

        <div>
            @include('assets.asset_type.filterAssetType')
        </div>

        <div
            @edit="handShowModalAssetTypeUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('common.table')
        </div>

         modal
        <div
            @save-asset-type="handleAssetTypeUI">
            @include('assets.asset_type.modalAssetTypeUI')
        </div>

        <div
            x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa loại tài sản này không ?'
            }"
            @ok="removeAssetType"
        >
            @include('common.modal-confirm')
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/api/apiAssetType.js"></script>
    <script src="/assets/js/api/apiAssetTypeGroup.js"></script>
    <script src="/assets/js/listAssetType.js"></script>
@endsection

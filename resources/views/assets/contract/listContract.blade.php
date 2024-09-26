@extends('layouts.app',[
    'title' => 'Quản lý hợp đồng'
])

@section('content')
    <div x-data="contract">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-primary" @click="handleShowModalContractUI('create')">
                Thêm mới
            </button>
            <button type="button" class="btn tw-bg-red-600 tw-text-white" @click="confirmDeleteMultiple">
                Xóa chọn
            </button>
        </div>

        <div>
            @include('assets.contract.filterContract')
        </div>

{{--        <div--}}
{{--            @edit="handShowModalContractUI('update', $event.detail.id)"--}}
{{--            @remove="confirmRemove($event.detail.id)"--}}
{{--            @change-page.window="changePage($event.detail.page)"--}}
{{--            @change-limit.window="changeLimit"--}}
{{--        >--}}
{{--            @include('common.table')--}}
{{--        </div>--}}

        {{-- modal--}}
        <div>
            <div
                @save="handleContractUI">
                @include('assets.contract.modalContractUI')
            </div>

{{--            <div--}}
{{--                x-data="{--}}
{{--                modalId: idModalConfirmDelete,--}}
{{--                contentBody: 'Bạn có chắc chắn muốn xóa loại tài sản này không ?'--}}
{{--            }"--}}
{{--                @ok="removeContract"--}}
{{--            >--}}
{{--                @include('common.modal-confirm')--}}
{{--            </div>--}}

{{--            <div--}}
{{--                x-data="{--}}
{{--                modalId: idModalConfirmDeleteMultiple,--}}
{{--                contentBody: 'Bạn có chắc chắn muốn xóa danh sách loại tài sản này không ?'--}}
{{--            }"--}}
{{--                @ok="deleteMultiple"--}}
{{--            >--}}
{{--                @include('common.modal-confirm')--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

@section('js')
    <script src="/js/asset/listContract.js"></script>
@endsection

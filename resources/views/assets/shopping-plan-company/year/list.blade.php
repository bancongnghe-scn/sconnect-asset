@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm'
])

@section('content')
    <div x-data="shoppingPlanCompanyYear">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
            <button type="button" class="btn tw-bg-red-600 tw-text-white"  @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                Xóa chọn
            </button>
        </div>

        <div>
            @include('assets.shopping-plan-company.year.filter')
        </div>

        <div
                @edit="handleShowModalUI('update', $event.detail.id)"
                @remove="confirmRemove($event.detail.id)"
                @view="handleShowModalInfo($event.detail.id)"
                @change-page.window="changePage($event.detail.page)"
                @change-limit.window="changeLimit"
        >
            @include('assets.shopping-plan-company.year.table')
        </div>

        {{--  modal--}}
        @include('assets.shopping-plan-company.year.modalUI')
{{--        @include('assets.shopping-plan-company.year.modalInfo')--}}
        <div
            x-data="{
                        modalId: idModalConfirmDelete,
                        contentBody: 'Bạn có chắc chắn muốn xóa kế hoạch mua sắm này không ?'
                    }"
            @ok="remove"
        >
            @include('common.modal-confirm')
        </div>

        <div
            x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách kế hoạch mua sắm này không ?'
            }"
            @ok="removeMultiple"
        >
            @include('common.modal-confirm')
        </div>
    </div>

@endsection

@section('js')
    @vite([
        'resources/js/assets/shoppingPlanCompanyYear.js',
        'resources/js/assets/api/apiShoppingPlanCompany.js',
        'resources/js/assets/api/apiShoppingPlanCompanyYear.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection

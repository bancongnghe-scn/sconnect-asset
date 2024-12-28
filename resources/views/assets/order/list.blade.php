@extends('layouts.app',[
    'title' => 'Quản lý đơn hàng'
])

@section('content')
    <div x-data="order">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tw-mt-8">
                            @include('assets.order.filters')
                        </div>

                        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
                            @can('order.crud')
                                <button class="btn btn-sc btn-sm px-3" type="button" @click="$('#modalSelectTypeCreate').modal('show')">
                                    <span>+ Thêm</span>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemove(true)" :disabled="window.checkDisableSelectRow">
                                    <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
                                </button>
                            @endcan
                        </div>

                        <div
                            @edit="handleShowModalUI('update', $event.detail.id)"
                            @remove="confirmRemove(false, $event.detail.id)"
                            @change-page.window="changePage($event.detail.page)"
                            @change-limit.window="changeLimit"
                        >
                            @include('assets.order.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--modal--}}
        <div>
            @include('assets.order.modalInsert')
        </div>

        <div>
            @include('assets.order.modalUpdate')
        </div>

        <div>
            @include('assets.order.modalSelectTypeCreate')
        </div>

        <div
            x-data="{
                    modalId: 'confirmRemove',
                    contentBody: 'Bạn có chắc chắn muốn xóa đơn hàng này không ?'
                }"
            @ok="$('#confirmRemove').modal('hide');$('#modalReason').modal('show')"
        >
            @include('common.modal-confirm')
        </div>

        <div @ok="remove">
            @include('common.modal-note', ['id' => 'modalReason', 'model' => 'reason'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/order.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/assets/api/order/apiOrder.js',
        'resources/js/assets/api/apiShoppingAsset.js',
        'resources/js/assets/api/apiShoppingAssetOrder.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiOrganization.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/week/apiShoppingPlanCompanyWeek.js',
    ])
@endsection

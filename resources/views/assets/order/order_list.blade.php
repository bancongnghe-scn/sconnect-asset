@extends('layouts.app',[
    'title' => 'Quản lý đơn hàng'
])

@section('content')
    <div x-data="order_list">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center form-group">
                            <div class="col-8 p-0">
                                @include('assets.order.filters')
                            </div>

                            <div class="d-flex tw-gap-x-2">
                                @can('order.crud')
                                    <button class="btn btn-sc btn-sm px-3" type="button" @click="$('#modalSelectTypeCreate').modal('show')">
                                        <span>+ Thêm</span>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemove(true)" :disabled="window.checkDisableSelectRow">
                                        <span><i class="bi bi-trash pr-1"></i>Xóa chọn</span>
                                    </button>
                                @endcan
                            </div>
                        </div>

                        <div
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
        'resources/js/assets/order/order_list.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/assets/api/order/apiOrder.js',
        'resources/js/assets/api/apiShoppingAsset.js',
        'resources/js/assets/api/apiShoppingAssetOrder.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiOrganization.js',
        'resources/js/assets/api/apiIndustry.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/week/apiShoppingPlanCompanyWeek.js',
    ])
@endsection

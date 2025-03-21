@extends('layouts.app',[
    'title' => 'Quản lý đơn hàng'
])

@section('content')
    <div x-data="order_list({{ request('tab_status') ?? null }})">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{--tab--}}
                        <div class="d-flex tw-gap-x-4">
                            <a href="#" class="tw-no-underline hover:tw-text-green-500" :class="tab_status === ORDER_STATUS_NEW ? 'active-link' : 'inactive-link'" @click="tab_status = ORDER_STATUS_NEW" x-text="`Mới tạo (${total_order.new})`"></a>
                            <a href="#" class="tw-no-underline hover:tw-text-green-500" :class="tab_status === ORDER_STATUS_TRANSIT ? 'active-link' : 'inactive-link'" @click="tab_status = ORDER_STATUS_TRANSIT" x-text="`Đang vận chuyển (${total_order.transit})`"></a>
                            <a href="#" class="tw-no-underline hover:tw-text-green-500" :class="tab_status === ORDER_STATUS_DELIVERED ? 'active-link' : 'inactive-link'" @click="tab_status = ORDER_STATUS_DELIVERED" x-text="`Đã giao hàng (${total_order.delivered})`"></a>
                            <a href="#" class="tw-no-underline hover:tw-text-green-500" :class="tab_status === ORDER_STATUS_WAREHOUSED ? 'active-link' : 'inactive-link'" @click="tab_status = ORDER_STATUS_WAREHOUSED" x-text="`Đã nhập kho (${total_order.warehoused})`"></a>
                            <a href="#" class="tw-no-underline hover:tw-text-green-500" :class="tab_status === ORDER_STATUS_CANCEL ? 'active-link' : 'inactive-link'" @click="tab_status = ORDER_STATUS_CANCEL" x-text="`Hủy (${total_order.cancel})`"></a>
                        </div>

                        <hr>

                        {{--filters--}}
                        <div class="d-flex justify-content-between align-items-center form-group">
                            <div class="col-8 p-0">
                                @include('assets.order.filters')
                            </div>

                            <div class="d-flex tw-gap-x-2">
                                @can('order.create')
                                    <button class="btn btn-sc btn-sm px-3" type="button" @click="$('#modalSelectTypeCreate').modal('show')">
                                        <span>+ Thêm</span>
                                    </button>
                                    <button x-show="[ORDER_STATUS_NEW, ORDER_STATUS_TRANSIT].includes(tab_status)" class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemove(true)" :disabled="window.checkDisableSelectRow">
                                        <span><i class="bi bi-trash pr-1"></i>Xóa chọn</span>
                                    </button>
                                @endcan
                            </div>
                        </div>

                        {{--table--}}
                        <div>
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

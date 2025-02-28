<div class="active-link tw-w-fit">Thông tin chung</div>
<div class="tw-grid tw-grid-cols-4 mt-3 gap-3" x-data="{ isDetail: false, isCreate: false, isUpdate: false}"
     x-init="isDetail = @json($action === 'detail'),isCreate = @json($action === 'create'), isUpdate = @json($action === 'update')"
>
    {{--  Hien thi khi tao   --}}
    <template x-if="+data.type === +ORDER_TYPE_CREATE_WITH_PLAN && isCreate">
        <div>
            <label>Lập đơn hàng từ<span class="tw-text-red-600 mb-0">*</span></label>
            <div>
                @include('common.select_custom.extent.select_single', [
                    'selected' => 'data.shopping_plan_company_id',
                    'options' => 'listShoppingPlanCompany',
                    'placeholder' => 'Chọn kế hoạch',
                ])
            </div>
        </div>
    </template>
    <template x-if="isCreate">
        <div>
            <label>Nhà cung cấp<span class="tw-text-red-600 mb-0">*</span></label>
            <div>
                @include('common.select_custom.extent.select_single', [
                    'selected' => 'data.supplier_id',
                    'options' => 'listSupplier',
                    'placeholder' => 'Chọn nhà cung cấp',
                ])
            </div>
        </div>
    </template>

    {{--  Hien thi khi xem chi tiet hoac update   --}}
    <div x-show="+data.type === +ORDER_TYPE_CREATE_WITH_PLAN && (isDetail || isUpdate)">
        <label>Lập đơn hàng từ<span class="tw-text-red-600 mb-0">*</span></label>
        <input type="text" x-model="data.plan_name" class="form-select" :disabled="!isCreate">
    </div>
    <div x-show="(isDetail || isUpdate)">
        <label>Nhà cung cấp<span class="tw-text-red-600 mb-0">*</span></label>
        <input type="text" x-model="data.supplier_name" class="form-select" :disabled="!isCreate">
    </div>
    <div class="tw-col-span-2">
        <label>Tên đơn hàng<span class="tw-text-red-600 mb-0">*</span></label>
        <input class="form-control" type="text" x-model="data.name" placeholder="Tên đơn hàng"
               :disabled=isDetail
        >
    </div>

    {{--  hien thi o tat ca  --}}
    <div>
        <label>Người phụ trách mua sắm<span
                class="tw-text-red-600 mb-0">*</span></label>
        <div>
            @include('common.user.select_single', [
                'selected' => 'data.purchasing_manager_id',
                'options' => 'listUser',
                'placeholder' => 'Chọn người phụ trách',
                'disabled' => 'isDetail'
            ])
        </div>
    </div>
    <div>
        <label>Ngày giao hàng</label>
        @include('common.datepicker.datepicker', [
            'placeholder' => "Ngày giao hàng",
            'model' => "data.delivery_date",
            'disabled' => 'isDetail'
        ])
    </div>
    <div>
        <label>Địa điểm giao hàng</label>
        <input type="text" class="form-control" x-model="data.delivery_location"
               placeholder="Địa điểm giao hàng" :disabled=isDetail>
    </div>
    <div>
        <label>Người liên hệ</label>
        <input type="text" class="form-control" x-model="data.contact_person"
               placeholder="Người liên hệ" x-bind:disabled=isDetail>
    </div>
    <div>
        <label>Thông tin liên hệ</label>
        <input type="text" class="form-control" x-model="data.contract_info"
               placeholder="Thông tin liên hệ" x-bind:disabled=isDetail>
    </div>
    <div>
        <label>Thời gian thanh toán</label>
        @include('common.datepicker.datepicker', [
            'placeholder' => "Thời gian thanh toán",
            'model' => "data.payment_time",
            'disabled' => 'isDetail'
        ])
    </div>
    <div x-data="{statusUpdate: {
            [ORDER_STATUS_NEW]: 'Mới tạo',
            [ORDER_STATUS_TRANSIT]: 'Đang vận chuyển',
            [ORDER_STATUS_DELIVERED]: 'Đã bàn giao',
            [ORDER_STATUS_CANCEL]: 'Hủy'
        }, statusOrder: []}" x-init="statusOrder = '{{$action ?? 'update'}}' === 'detail' ? LIST_STATUS_ORDER : statusUpdate">
        <label>Trạng thái</label>
        @include('common.select_custom.simple.select_single', [
             'selected' => 'data.status',
             'options' => 'statusOrder',
             'placeholder' => 'Chọn trạng thái',
             'disabled' => 'isDetail'
        ])
    </div>
</div>

<div class="active-link tw-w-fit">Thông tin chung</div>
<div class="tw-grid tw-grid-cols-4 mt-3 gap-3" x-data="{ isDisabled: @json($disabled) }">
    <div x-show="+data.type === +ORDER_TYPE_CREATE_WITH_PLAN">
        <label>Lập đơn hàng từ<span class="tw-text-red-600 mb-0">*</span></label>
        <input type="text" x-model="data.plan_name" class="form-select" disabled>
    </div>
    <div>
        <label>Nhà cung cấp<span class="tw-text-red-600 mb-0">*</span></label>
        <input type="text" x-model="data.supplier_name" class="form-select" disabled>
    </div>
    <div class="tw-col-span-2">
        <label>Tên đơn hàng<span class="tw-text-red-600 mb-0">*</span></label>
        <input class="form-control" type="text" x-model="data.name" placeholder="Tên đơn hàng"
               :disabled=isDisabled
        >
    </div>
    <div>
        <label>Người phụ trách mua sắm<span
                class="tw-text-red-600 mb-0">*</span></label>
        <div>
            @include('common.user.select_single', [
                'selected' => 'data.purchasing_manager_id',
                'options' => 'listUser',
                'placeholder' => 'Chọn người phụ trách',
                'disabled' => 'isDisabled'
            ])
        </div>
    </div>
    <div>
        <label>Ngày giao hàng</label>
        @include('common.datepicker.datepicker', [
            'placeholder' => "Ngày giao hàng",
            'model' => "data.delivery_date",
            'disabled' => 'isDisabled'
        ])
    </div>
    <div>
        <label>Địa điểm giao hàng</label>
        <input type="text" class="form-control" x-model="data.delivery_location"
               placeholder="Địa điểm giao hàng" :disabled=isDisabled>
    </div>
    <div>
        <label>Người liên hệ</label>
        <input type="text" class="form-control" x-model="data.contact_person"
               placeholder="Người liên hệ" x-bind:disabled=isDisabled>
    </div>
    <div>
        <label>Thông tin liên hệ</label>
        <input type="text" class="form-control" x-model="data.contract_info"
               placeholder="Thông tin liên hệ" x-bind:disabled=isDisabled>
    </div>
    <div>
        <label>Thời gian thanh toán</label>
        @include('common.datepicker.datepicker', [
            'placeholder' => "Thời gian thanh toán",
            'model' => "data.payment_time",
            'disabled' => 'isDisabled'
        ])
    </div>
    <div>
        <label>Trạng thái</label>
        @include('common.select_custom.simple.select_single', [
             'selected' => 'data.status',
             'options' => 'LIST_STATUS_ORDER',
             'placeholder' => 'Chọn trạng thái',
             'disabled' => 'isDisabled'
        ])
    </div>
</div>

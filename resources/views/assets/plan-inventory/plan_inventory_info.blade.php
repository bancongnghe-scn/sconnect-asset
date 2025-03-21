<div class="tw-grid tw-grid-cols-4 tw-gap-4" x-data="{disabled: @json($disabled)}">
    <div>
        <label class="tw-font-bold">Tên kế hoạch<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
        <input class="form-control" type="text" x-model="data.name" placeholder="Nhập tên kế hoạch" :disabled=disabled>
    </div>
    <div>
        <label class="tw-font-bold">Thời gian<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
        @include('common.datepicker.datepicker_range', [
            'placeholder' => 'Chọn khoảng thời gian',
            'start' => 'data.start_time',
            'end' => 'data.end_time',
            'disabled' => 'disabled'
        ])
    </div>
    <div>
        <label class="tw-font-bold">Đơn vị<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
        <div>
            @include('common.select_custom.extent.select_multiple', [
                'placeholder' => 'Chọn đơn vị',
                'options' => 'listOrganization',
                'selected' => 'data.organization_ids',
                'disabled' => 'disabled || data.status !== STATUS_INVENTORY_NEW'
            ])
        </div>
    </div>
    <div>
        <label class="tw-font-bold">Loại tài sản<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
        <div>
            @include('common.select_custom.extent.select_multiple', [
                'placeholder' => 'Chọn loại tài sản',
                'options' => 'listAssetType',
                'selected' => 'data.asset_type_ids',
                'disabled' => 'disabled || data.status !== STATUS_INVENTORY_NEW'
            ])
        </div>
    </div>
    <div>
        <label>Kiểm kê<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
        <div class="d-flex tw-gap-x-3">
            <div class="d-flex align-middle tw-gap-x-2">
                <input type="radio" id="manual" :value="TYPE_INVENTORY_NOT_AUTO" x-model="data.type_inventory" :disabled='disabled || data.status !== STATUS_INVENTORY_NEW'>
                <label class="form-check-label" for="manual">Thủ công</label>
            </div>
            <div class="d-flex align-middle tw-gap-x-2">
                <input type="radio" id="auto" :value="TYPE_INVENTORY_AUTO" x-model="data.type_inventory" :disabled='disabled || data.status !== STATUS_INVENTORY_NEW'>
                <label class="form-check-label" for="auto">Tự động</label>
            </div>
        </div>
        <span x-show="+data.type_inventory === TYPE_INVENTORY_AUTO" class="text-red">Lưu ý: kiểm kê tài sản tự động chỉ áp dụng với các loại tài sản(CPU, Card, Ram, HDD, SSD, Main)</span>
    </div>
    <div>
        <label class="tw-font-bold">Người tham gia</label>
        @include('common.user.select_multiple', [
            'placeholder' => 'Chọn người tham gia',
            'options' => 'listUser',
            'selected' => 'data.user_ids',
            'disabled' => 'disabled'
        ])
    </div>
    <div>
        <label class="tw-font-bold">Mô tả</label>
        <textarea class="form-control" x-model="data.note" placeholder="Nhập mô tả" style="min-height: 4rem" :disabled=disabled></textarea>
    </div>
    <div>
        <label></label>
        <div class="align-content-between mt-2">
            <input type="checkbox" class="" id="exampleCheck1" x-model="data.sent_notification" :disabled='disabled || data.status !== STATUS_INVENTORY_NEW'>
            <label class="form-check-label" for="exampleCheck1">Gửi thông báo cho đơn vị</label>
        </div>
    </div>
</div>

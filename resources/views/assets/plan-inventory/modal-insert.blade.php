<div class="modal fade" id="modalCreatePlanInventory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm mới kế hoạch kiểm kê</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column tw-gap-y-3">
                    <div>
                        <label class="tw-font-bold">Tên kế hoạch<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <input class="form-control" type="text" x-model="data.name" placeholder="Nhập tên kế hoạch">
                    </div>
                    <div>
                        <label class="tw-font-bold">Thời gian<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        @include('common.datepicker.datepicker_range', [
                            'placeholder' => 'Chọn khoảng thời gian',
                            'start' => 'data.start_time',
                            'end' => 'data.end_time'
                        ])
                    </div>
                    <div>
                        <label>Kiểm kê<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <div class="d-flex tw-gap-x-3" x-data="{data : {type_inventory: TYPE_INVENTORY_NOT_AUTO}}">
                            <div class="d-flex align-middle tw-gap-x-2">
                                <input type="radio" id="manual" :value="TYPE_INVENTORY_NOT_AUTO" x-model="data.type_inventory">
                                <label class="form-check-label" for="manual">Thủ công</label>
                            </div>
                            <div class="d-flex align-middle tw-gap-x-2">
                                <input type="radio" id="auto" :value="TYPE_INVENTORY_AUTO" x-model="data.type_inventory">
                                <label class="form-check-label" for="auto">Tự động</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="tw-font-bold">Đơn vị<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <div>
                            @include('common.select_custom.extent.select_multiple', [
                                'placeholder' => 'Chọn đơn vị',
                                'options' => 'listOrganization',
                                'selected' => 'data.organization_ids'
                            ])
                        </div>
                    </div>
                    <div>
                        <label class="tw-font-bold">Loại tài sản<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <div>
                            @include('common.select_custom.extent.select_multiple', [
                                'placeholder' => 'Chọn loại tài sản',
                                'options' => 'listAssetType',
                                'selected' => 'data.asset_type_ids'
                            ])
                        </div>
                    </div>
                    <div>
                        <label class="tw-font-bold">Người tham gia</label>
                        @include('common.select_custom.extent.select_multiple', [
                            'placeholder' => 'Chọn người tham gia',
                            'options' => 'listUser',
                            'selected' => 'data.user_ids'
                        ])
                    </div>
                    <div>
                        <label class="tw-font-bold">Mô tả</label>
                        <textarea class="form-control" style="min-height: 7rem" x-model="data.note" placeholder="Nhập mô tả"></textarea>
                    </div>
                    <div>
                        <input type="checkbox" class="" id="exampleCheck1" x-model="data.sent_notification">
                        <label class="form-check-label" for="exampleCheck1">Gửi thông báo cho đơn vị</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



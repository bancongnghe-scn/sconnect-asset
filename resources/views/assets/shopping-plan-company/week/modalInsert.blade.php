<div class="modal fade" id="idModalInsert" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm mới kế hoạch</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                    <div>
                        <label class="tw-font-bold">Kế hoạch quý<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <span>
                            @include('common.select2.modal.extent.select2_single_modal', [
                               'placeholder' => 'Chọn kế hoạch quý',
                               'model' => 'data.plan_quarter_id',
                               'values' => 'listPlanCompanyQuarter',
                            ])
                        </span>
                    </div>

                    <div>
                        <label class="tw-font-bold">Tháng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <span>
                            @include('common.select2.modal.simple.select2_single_modal', [
                               'placeholder' => 'Chọn tháng',
                               'model' => 'data.month',
                               'values' => 'LIST_MONTHS',
                            ])
                        </span>
                    </div>

                    <div>
                        <label class="tw-font-bold">Tuần<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <span>
                            @include('common.select2.modal.simple.select2_single_modal', [
                               'placeholder' => 'Chọn tuần',
                               'model' => 'data.time',
                               'values' => 'LIST_WEEK',
                            ])
                        </span>
                    </div>

                    <div>
                        <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <span>
                            @include('common.datepicker.datepicker_range', [
                                'placeholder' => 'Chọn thời gian đăng ký',
                                'start' => 'data.start_time',
                                'end' => 'data.end_time'
                            ])
                        </span>
                    </div>

                    <template x-if="listUser.length > 0">
                        <div>
                            <label class="form-label">Người quan sát</label>
                            @include('common.select2.modal.extent.select2_multiple_modal', [
                                'placeholder' => 'Chọn người quan sát',
                                'model' => 'data.monitor_ids',
                                'values' => 'listUser'
                            ])
                        </div>
                    </template>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="create" type="button" class="btn btn-sc">Lưu</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

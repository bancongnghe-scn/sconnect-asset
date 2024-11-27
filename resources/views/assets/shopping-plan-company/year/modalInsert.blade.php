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
                        <label class="tw-font-bold">Năm<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        @include('common.datepicker.datepicker_year',['model' => 'data.time'])
                    </div>

                    <div>
                        <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <span @date-change="data.start_time = $event.detail.start; data.end_time = $event.detail.end">
                            @include('common.datepicker.datepicker_range', ['placeholder' => 'Chọn thời gian đăng ký'])
                        </span>
                    </div>

                    <template x-if="listUser.length > 0">
                        <div>
                            <label class="form-label">Người quan sát</label>
                            <select class="form-select select2" x-model="data.monitor_ids"
                                    multiple="multiple" data-placeholder="Chọn người quan sát"
                                    x-init="$nextTick(() => {
                                        $($el).on('change', () => {data.monitor_ids = $($el).val(); console.log(11111)});
                                    })"
                            >
                                <template x-for="value in listUser" :key="value.id">
                                    <option :value="value.id" x-text="value.name"></option>
                                </template>
                            </select>
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


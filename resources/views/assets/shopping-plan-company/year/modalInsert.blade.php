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
                        @include('common.datepicker.datepicker_range', [
                                'placeholder' => 'Chọn thời gian đăng ký',
                                'start' => 'data.start_time',
                                'end' => 'data.end_time'
                        ])
                    </div>

                    <template x-if="listUser.length > 0">
                        <div>
                            <label class="form-label">Người quan sát</label>
                            @include('common.user.select_multiple', [
                                'placeholder' => 'Chọn người quan sát',
                                'options' => 'listUser',
                                'selected' => 'data.monitor_ids'
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



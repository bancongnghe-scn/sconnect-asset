<div class="modal fade" id="idModalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' kế hoạch'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                    <div>
                        <label class="tw-font-bold">Năm<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <input type="text" class="form-control yearPicker" x-model="data.time" id="selectYear" placeholder="Chọn năm" autocomplete="off">
                    </div>

                    <div>
                        <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <input type="text" class="form-control dateRange" id="selectDateRegister" placeholder="Chọn thời gian đăng ký" autocomplete="off">
                    </div>

                    <div>
                        <label class="form-label">Người quan sát</label>
                        <select class="form-select select2" x-model="data.monitor_ids" id="selectUser" multiple="multiple" data-placeholder="Chọn người quan sát">
                            <template x-for="value in listUser" :key="value.id">
                                <option :value="value.id" x-text="value.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-sc">Lưu</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>


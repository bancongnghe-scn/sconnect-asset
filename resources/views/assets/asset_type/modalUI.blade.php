<div class="modal fade" id="modalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' loại tài sản'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Tên loại tài sản<label class="tw-text-red-600 mb-0">*</label></label>
                        <input type="text" class="form-control" x-model="data.name" placeholder="Nhập tên loại tài sản">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Nhóm tài sản<label class="tw-text-red-600 mb-0">*</label></label>
                        <select class="form-select select2" x-model="data.asset_type_group_id" id="selectAssetTypeGroup">
                            <option value="">Chọn nhóm tài sản</option>
                            <template x-for="value in listAssetTypeGroup" :key="value.id">
                                <option :value="value.id" x-text="value.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Đơn vị tính<label class="tw-text-red-600 mb-0">*</label></label>
                        <select class="form-control select2" x-model="data.measure" id="selectMeasure">
                            <option value="">Chọn đơn vị</option>
                            <template x-for="(value, key) in listMeasure">
                                <option :value="key" x-text="value"></option>
                            </template>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Thời gian bảo dưỡng(tháng)<label class="tw-text-red-600 mb-0">*</label></label>
                        <input type="number" class="form-control" x-model="data.maintenance_months" placeholder="Nhập thời gian bảo dưỡng">
                    </div>
                </div>
                <div class="row mb-3">
                    <div>
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control tw-h-40" x-model="data.description" placeholder="Nhập ghi chú"></textarea>
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

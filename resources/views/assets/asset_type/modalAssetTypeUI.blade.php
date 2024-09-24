<div class="modal fade" id="modalAssetTypeUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleAction + ' loại tài sản'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Tên loại tài sản<label class="tw-text-red-600">*</label></label>
                        <input type="text" class="form-control" x-model="assetType.name">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Nhóm tài sản<label class="tw-text-red-600">*</label></label>
                        <select class="form-control" x-model="assetType.asset_type_group_id">
                            <option value="">Chọn nhóm tài sản ...</option>
                            <template x-for="assetTypeGroup in listAssetTypeGroup" :key="assetTypeGroup.id">
                                <option :value="assetTypeGroup.id" x-text="assetTypeGroup.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Đơn vị tính<label class="tw-text-red-600">*</label></label>
                        <select class="form-control" x-model="assetType.measure">
                            <option value="">Chọn đơn vị ...</option>
                            <template x-for="(value, key) in listMeasure" :key="key">
                                <option :value="key" x-text="value"></option>
                            </template>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Thời gian bảo dưỡng(tháng)<label class="tw-text-red-600">*</label></label>
                        <input type="number" class="form-control" x-model="assetType.maintenance_months">
                    </div>
                </div>
                <div class="row mb-3">
                    <div>
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control tw-h-40" x-model="assetType.description"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="$dispatch('save-asset-type')" type="button" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>

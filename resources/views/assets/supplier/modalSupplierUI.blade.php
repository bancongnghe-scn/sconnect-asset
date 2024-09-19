<div class="modal fade" id="modalSupplierUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleAction + ' nhà cung cấp'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Tên nhà cung cấp</label>
                            <input type="text" class="form-control" x-model="supplier.name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control tw-h-40" x-model="supplier.description"></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Thông tin liên hệ</label>
                            <input type="text" class="form-control" x-model="supplier.name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="$dispatch('save-supplier')" type="button" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>

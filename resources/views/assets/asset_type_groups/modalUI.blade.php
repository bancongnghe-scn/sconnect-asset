<div class="modal fade" id="modalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' nhóm tài sản'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Tên nhóm tài sản<label class="tw-text-red-600 mb-0">*</label></label>
                    <input type="text" class="form-control" x-model="data.name" placeholder="Nhập tên nhóm tài sản">
                </div>
                <div class="mb-3">
                    <label for="floatingTextarea2">Mô tả</label>
                    <textarea class="form-control tw-h-40" x-model="data.description" placeholder="Nhập mô tả"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-sc">Lưu</button>
            </div>
        </div>
    </div>
</div>

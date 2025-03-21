<div class="modal fade" id="idModalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleModal + ' quyền'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên quyền<label class="color-red">*</label></label>
                    <input type="text" class="form-control" x-model="permission.name" placeholder="Nhập tên quyền">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control tw-h-40" x-model="permission.description" placeholder="Nhập ghi chú"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Danh sách nhân viên</label>
                    @include('common.user.select_multiple', [
                        'placeholder' => 'Chọn danh sách nhân viên',
                        'options' => 'listUser',
                        'selected' => 'permission.user_ids'
                    ])
                </div>

                <div class="mb-3">
                    <label class="form-label">Danh sách vai trò</label>
                    @include('common.select_custom.extent.select_multiple', [
                       'placeholder' => 'Chọn danh sách vai trò',
                       'options' => 'listRole',
                       'selected' => 'permission.role_ids'
                    ])
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-sc">Lưu</button>
            </div>
        </div>
    </div>
</div>


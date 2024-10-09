<div class="modal fade" id="idModalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleModal + ' quyền'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên quyền<label class="tw-text-red-600">*</label></label>
                    <input type="text" class="form-control" x-model="permission.name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control tw-h-40" x-model="permission.description"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Danh sách nhân viên</label>
                    <select class="form-control select2" multiple="multiple" id="selectUsers" data-placeholder="Chọn ..." x-model="permission.user_ids">
                        <template x-for="user in listUser" :key="user.id">
                            <option :value="user.id" x-text="user.name"></option>
                        </template>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Danh sách vai trò</label>
                    <select class="form-control select2" multiple="multiple" id="selectRoles" data-placeholder="Chọn ..." x-model="permission.role_ids">
                        <template x-for="role in listRole" :key="role.id">
                            <option :value="role.id" x-text="role.name"></option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-sc">Lưu</button>
            </div>
        </div>
    </div>
</div>


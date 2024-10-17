<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-end form-group">
                    <div class="col-3">
                        <label>Tên vai trò</label>
                        <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên vài trò">
                    </div>
                    <div class="col-3">
                        <label>Menu</label>
                        <select class="form-select select2" multiple="multiple" id="filterRoles" data-placeholder="Chọn danh sách vai trò ..." x-model="filters.role_ids">
                            <template x-for="role in listRole" :key="role.id">
                                <option :value="role.id" x-text="role.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button @click="list(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

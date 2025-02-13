<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-end form-group">
                    <div class="col-3">
                        <label>Tên menu</label>
                        <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên menu">
                    </div>
                    <div class="col-3">
                        <label>Vai trò</label>
                        @include('common.select_custom.extent.select_multiple', [
                           'placeholder' => 'Chọn danh sách vai trò',
                           'options' => 'listRole',
                           'selected' => 'filters.role_ids',
                        ])
                    </div>
                    <div class="col-auto">
                        <button @click="list(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

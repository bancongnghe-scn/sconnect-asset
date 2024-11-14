<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex align-items-end form-group">
                <div class="col-4">
                    <label class="tw-font-bold">Tên nhóm tài sản</label>
                    <input type="text" class="form-control" x-model="filters.name"
                           placeholder="Nhập tên nhóm tài sản">
                </div>
                <div class="col-auto">
                    <button @click="list(filters)" type="button" class="btn btn-sc">Tìm kiếm</button>
                </div>
                <div class="col-auto">
                    <button @click="reloadPage()" type="button" class="btn btn-secondary">Xóa lọc</button>
                </div>
            </div>
        </div>
    </div>
</div>

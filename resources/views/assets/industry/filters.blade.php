<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end form-group">
                <div class="col-3">
                    <label class="tw-font-bold">Tên ngành hàng</label>
                    <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên ngành hàng">
                </div>
                <div class="col-auto">
                    <button @click="list(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                </div>
                <div class="col-auto">
                    <button @click="reloadPage()" type="button" class="btn btn-secondary">Xóa lọc</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-row align-items-end form-group">
    <div class="col-3">
        <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên ngành hàng" @keydown.enter="list(filters)">
    </div>
    <div class="col-auto">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
    </div>
</div>

<div class="d-flex align-items-end form-group">
    <div class="col-4 pl-0">
        <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên nhóm tài sản" @keydown.enter="list(filters)">
    </div>
    <div class="col-auto">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
    </div>
</div>

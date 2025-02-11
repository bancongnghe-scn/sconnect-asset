<div class="d-flex flex-row align-items-end form-group">
    <div class="col-3 pl-0">
        <input type="text" class="form-control" x-model="filters.code_name" placeholder="Nhập tên mã/nhà cung cấp" @keydown.enter="list(filters)">
    </div>
    <div class="col-3">
        @include('common.select_custom.extent.select_single', [
            'selected' => 'filters.industry_ids',
            'options' => 'listIndustry',
            'placeholder' => 'Chọn ngành hàng',
        ])
    </div>
    <div class="col-3">
        @include('common.select_custom.simple.select_single', [
            'selected' => 'filters.status',
            'options' => 'LIST_STATUS_SUPPLIER',
            'placeholder' => 'Chọn đánh giá',
        ])
    </div>

    <div class="col-auto">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
    </div>
</div>

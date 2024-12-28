<div class="d-flex flex-row align-items-end form-group">
    <div class="col-3">
        <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên loại tài sản" @keydown.enter="list(filters)">
    </div>
    <div class="col-3">
        <div>
            @include('common.select_custom.extent.select_single', [
                'selected' => 'filters.asset_type_group_id',
                'options' => 'listAssetTypeGroup',
                'placeholder' => 'Chọn nhóm tài sản',
            ])
        </div>
    </div>
    <div class="col-auto">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
    </div>
</div>

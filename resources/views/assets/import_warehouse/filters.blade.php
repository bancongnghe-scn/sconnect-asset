<div class="d-flex flex-row align-items-end pb-4">
    <div class="col-2">
        <input class="form-control" type="text" x-model="filters.code_name" placeholder="Tên/mã phiếu nhập" @keydown.enter="list(filters)">
    </div>

    <div class="col-2">
        <div>
            @include('common.select_custom.simple.select_single', [
                 'selected' => 'filters.status',
                 'options' => 'LIST_STATUS_IMPORT_WAREHOUSE',
                 'placeholder' => 'Chọn trạng thái',
            ])
        </div>
    </div>

    <div class="col-2">
        <div>
            @include('common.select_custom.extent.select_single', [
                'selected' => 'filters.created_by',
                'options' => 'listUser',
                'placeholder' => 'Người nhập',
            ])
        </div>
    </div>

    <div class="col-2">
        @include('common.datepicker.datepicker', ['placeholder' => "Thời gian", 'model' => "filters.created_at"])
    </div>

    <div class="col-auto">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
    </div>
</div>

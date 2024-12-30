<div class="d-flex flex-row align-items-end form-group">
    <div class="col-2">
        <input class="form-control" type="text" x-model="filters.code_name"
               placeholder="Tên/số đơn hàng"
               @keydown.enter="list(filters)">
    </div>
    <div class="col-2">
        @include('common.datepicker.datepicker', [
            'placeholder' => "Ngày đơn hàng",
            'model' => "filters.created_at",
            'id' => 'filters.created_at'
        ])
    </div>
    <div class="col-2">
        <div>
            <select class="form-select" x-model="filters.status">
                <option value="#">Chọn trạng thái</option>
                <template x-for="(value, key) in LIST_STATUS_ORDER">
                    <option :value="key" x-text="value"></option>
                </template>
            </select>
        </div>
    </div>
    <div class="col-auto">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
    </div>
</div>

<div class="d-grid tw-grid-cols-4 tw-gap-x-4 align-items-center">
    <div>
        <input class="form-control" type="text" x-model="filters.code_name"
               placeholder="Tên/số đơn hàng"
               @keydown.enter="list(filters)">
    </div>
    <div>
        @include('common.datepicker.datepicker', [
            'placeholder' => "Ngày đơn hàng",
            'model' => "filters.created_at",
            'id' => 'filters.created_at'
        ])
    </div>
    <div>
        <button @click="reloadPage()" type="button" class="btn btn-sm btn-outline-danger">Xóa lọc</button>
    </div>
</div>

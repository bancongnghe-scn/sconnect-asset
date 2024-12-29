<div class="d-flex flex-wrap align-items-end form-group tw-gap-y-3">
    <div class="col-3">
        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Nhập tên/mã hợp đồng" @keydown.enter="list(filters)">
    </div>
    <div class="col-3">
        @include('common.select_custom.simple.select_single', [
                'selected' => 'filters.type',
                'options' => 'TYPE_CONTRACT',
                'placeholder' => 'Chọn loại hợp đồng',
                'id' => 'filters.type'
        ])
    </div>
    <div class="col-2">
        @include('common.select_custom.simple.select_single', [
               'selected' => 'filters.status',
               'id' => 'filters.status',
               'options' => 'STATUS_CONTRACT',
               'placeholder' => 'Chọn trạng thái',
       ])
    </div>
    <div class="col-3">
        @include('common.datepicker.datepicker', ['placeholder' => "Ngày ký", 'model' => "filters.signing_date"])
    </div>
    <div class="col-3">
        @include('common.datepicker.datepicker', ['placeholder' => "Ngày hiệu lực", 'model' => "filters.from"])
    </div>
    <div class="col-auto">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
    </div>
</div>

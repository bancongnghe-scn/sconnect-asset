<div class="row">
    <div class="col-12">
        <div class="d-flex flex-wrap align-items-end form-group tw-gap-y-3">
            <div class="col-3 pl-0">
                <input type="text" class="form-control" x-model="filters.name_code" placeholder="Nhập tên/mã phụ lục" @keydown.enter="list(filters)">
            </div>
            <div class="col-3">
                @include('common.select_custom.extent.select_single', [
                    'selected' => 'filters.contract_id',
                    'options' => 'listContract',
                    'placeholder' => 'Chọn hợp đồng',
                ])
            </div>
            <div class="col-2">
                @include('common.select_custom.simple.select_single', [
                    'selected' => 'filters.status',
                    'options' => 'STATUS_APPENDIX',
                    'placeholder' => 'Chọn trạng thái',
                ])
            </div>
            <div class="col-3">
                @include('common.datepicker.datepicker', ['placeholder' => "Ngày ký", 'model' => "filters.signing_date"])
            </div>
            <div class="col-3 pl-0">
                @include('common.datepicker.datepicker', ['placeholder' => "Ngày hiệu lực", 'model' => "filters.from"])
            </div>
            <div class="col-auto">
                <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
            </div>
        </div>
    </div>
</div>

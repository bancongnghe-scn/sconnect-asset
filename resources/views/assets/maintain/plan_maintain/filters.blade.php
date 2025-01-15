<div class="tw-grid tw-grid-cols-5 tw-gap-x-3">
    <div>
        <input class="form-control" type="text" x-model="filters.name_code" placeholder="Tên/mã kế hoạch" @keydown.enter="list(filters)">
    </div>
    <div>
        @include('common.datepicker.datepicker_range', [
           'placeholder' => 'Chọn thời gian',
           'start' => 'filters.start_time',
           'end' => 'filters.end_time',
        ])
    </div>
    <div>
        @include('common.select_custom.extent.select_single', [
           'selected' => 'filters.supplier_id',
           'options' => 'listSupplier',
           'placeholder' => 'Đơn vị trí thực hiện bảo dưỡng',
        ])
    </div>
    <div>
        @include('common.select_custom.simple.select_single', [
           'selected' => 'filters.status',
           'options' => 'LIST_STATUS_PLAN_MAINTAIN',
           'placeholder' => 'Trạng thái',
        ])
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
        <div>
            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModalUI('create')">
                <span>+ Thêm</span>
            </button>
            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmDeleteMultiple" :disabled="window.checkDisableSelectRow">
                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
            </button>
        </div>
    </div>
</div>

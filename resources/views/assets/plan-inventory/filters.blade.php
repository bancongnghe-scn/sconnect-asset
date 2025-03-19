<div class="tw-grid tw-grid-cols-5 tw-gap-x-3">
    <div>
        <input type="text" class="form-control" x-model="filters.name" placeholder="Tên kế hoạch" @keydown.enter="list(filters)">
    </div>
    <div>
        @include('common.datepicker.datepicker_range', [
            'placeholder' => 'Thời gian',
            'start' => 'filters.start_time',
            'end' => 'filters.end_time',
        ])
    </div>
    <div>
        @include('common.select_custom.simple.select_single', [
            'selected' => 'filters.status',
            'options' => 'LIST_STATUS_PLAN_INVENTORY',
            'placeholder' => 'Trạng thái',
        ])
    </div>
    <div class="tw-col-span-2 d-flex justify-content-between align-items-center">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
        <div>
            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModalInsert()">
                <span>+ Thêm</span>
            </button>
            <button class="btn btn-sm btn-outline-danger" type="button"
                    :disabled="window.checkDisableSelectRow"
                    @click="confirmRemovePlanInventory(true)"
            >
                <span><i class="bi bi-trash pr-1"></i>Xóa chọn</span>
            </button>
        </div>
    </div>
</div>

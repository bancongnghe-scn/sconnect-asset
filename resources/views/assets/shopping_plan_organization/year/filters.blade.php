<div class="d-grid tw-grid-cols-12 tw-gap-x-4 align-items-center form-group">
    <div class="tw-col-span-3">
        @include('common.datepicker.datepicker_year',['model' => 'filters.time'])
    </div>
    <div class="tw-col-span-2">
        @include('common.select_custom.simple.select_single', [
            'selected' => 'filters.status',
            'options' => 'STATUS_SHOPPING_PLAN_ORGANIZATION',
            'placeholder' => 'Chọn trạng thái',
        ])
    </div>
    <div>
        <button @click="reloadPage()" type="button" class="btn btn-sm btn-outline-danger">Xóa lọc</button>
    </div>
</div>

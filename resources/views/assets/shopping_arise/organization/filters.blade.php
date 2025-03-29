<div class="d-flex justify-content-between align-items-center form-group">
    <div class="col-10 p-0">
        <div class="d-grid tw-grid-cols-12 tw-gap-x-4 align-items-center">
            <div class="tw-col-span-3 pl-0">
                <input class="form-control" type="text" x-model="filters.name" placeholder="Tên đề xuất" @keydown.enter="getListShoppingArise(filters)">
            </div>

            <div class="tw-col-span-3">
               @include('common.datepicker.datepicker_range', [
                   'placeholder' => "Thời gian đề xuất",
                   'start' => 'filters.start_time',
                   'end' => 'filters.end_time',
               ])
            </div>

            <div class="tw-col-span-2">
                @include('common.select_custom.simple.select_single', [
                    'selected' => 'filters.status',
                    'options' => 'STATUS_SHOPPING_ARISE',
                    'placeholder' => 'Trạng thái',
                ])
            </div>
            <div class="tw-col-span-3">
                <button @click="reloadPage()" type="button" class="btn btn-sm btn-outline-danger">Xóa lọc</button>
            </div>
        </div>
    </div>
    @can('shopping_plan_company.year_quarter.crud')
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModalCreate">
                <span>+ Thêm</span>
            </button>
            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemove('multiple')" :disabled="window.checkDisableSelectRow">
                <span><i class="bi bi-trash pr-1"></i>Xóa chọn</span>
            </button>
        </div>
    @endcan
</div>


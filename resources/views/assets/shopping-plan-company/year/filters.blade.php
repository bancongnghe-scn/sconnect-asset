<div class="d-flex justify-content-between align-items-center form-group">
    <div class="col-10 p-0">
        <div class="d-grid tw-grid-cols-12 tw-gap-x-4 align-items-center">
            <div class="tw-col-span-3">
                @include('common.datepicker.datepicker_year',['model' => 'filters.time'])
            </div>
            <div class="tw-col-span-3">
                @include('common.select_custom.simple.select_single', [
                    'selected' => 'filters.status',
                    'options' => 'STATUS_SHOPPING_PLAN_COMPANY',
                    'placeholder' => 'Chọn trạng thái',
                ])
            </div>
            <div class="tw-col-span-3">
                <button @click="reloadPage()" type="button" class="btn btn-sm btn-outline-danger">Xóa lọc</button>
            </div>
        </div>
    </div>
    @can('shopping_plan_company.crud')
        <div class="d-flex tw-gap-x-2 align-items-center">
            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModal(null, 'create')">
                <span>+ Thêm</span>
            </button>
            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                <span><i class="bi bi-trash pr-1"></i>Xóa chọn</span>
            </button>
        </div>
    @endcan
</div>

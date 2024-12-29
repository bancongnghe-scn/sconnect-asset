<div class="row">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-3 align-items-end form-group">
            <div class="col-3 pl-0">
                @include('common.select_custom.extent.select_single', [
                    'selected' => 'filters.plan_quarter_id',
                    'options' => 'listPlanCompanyQuarter',
                    'placeholder' => 'Chọn kế hoạch quý',
                ])
            </div>

            <div class="col-2">
                @include('common.select_custom.simple.select_single', [
                   'selected' => 'filters.time',
                   'options' => 'LIST_WEEK',
                   'placeholder' => 'Chọn tuần',
                ])
            </div>

            <div class="col-2">
                @include('common.select_custom.simple.select_single', [
                   'selected' => 'filters.status',
                   'options' => 'STATUS_SHOPPING_PLAN_ORGANIZATION',
                   'placeholder' => 'Chọn trạng thái',
                ])
            </div>
            <div class="col-auto">
                <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
            </div>
        </div>
    </div>
</div>

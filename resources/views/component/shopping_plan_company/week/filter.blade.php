<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-end form-group">
                    <div class="col-3">
                        <label class="tw-font-bold">Kế hoạch quý</label>
                        <span x-data="{
                            model: filters.plan_quarter_id,
                            init() {this.$watch('filters.plan_quarter_id', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                        }"
                              @select-change="filters.plan_quarter_id = $event.detail">
                            @include('common.select2.extent.select2', [
                                  'placeholder' => 'Chọn kế hoạch quý',
                                  'values' => 'listPlanCompanyQuarter'
                            ])
                        </span>
                    </div>

                    <div class="col-2">
                        <label class="tw-font-bold">Tuần</label>
                        <span x-data="{
                            model: filters.time,
                            init() {this.$watch('filters.time', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                        }"
                              @select-change="filters.time = $event.detail">
                            @include('common.select2.simple.select2_single', [
                                  'placeholder' => 'Chọn tuần',
                                  'values' => 'LIST_WEEK'
                            ])
                        </span>
                    </div>

                    <div class="col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <span x-data="{
                                model: filters.status,
                                init() {this.$watch('filters.status', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                            }"
                            @select-change="filters.status = $event.detail"
                        >
                            @include('common.select2.simple.select2_multiple', [
                                'placeholder' => 'Chọn trạng thái',
                                'values' => 'STATUS_SHOPPING_PLAN_COMPANY'
                            ])
                        </span>
                    </div>
                    <div class="col-auto">
                        <button @click="list(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                    </div>
                    <div class="col-auto">
                        <button @click="reloadPage()" type="button" class="btn btn-secondary">Xóa lọc</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

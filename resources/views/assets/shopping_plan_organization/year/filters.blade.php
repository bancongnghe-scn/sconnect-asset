<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-end form-group">
                    <div class="col-3">
                        <label class="tw-font-bold">Năm</label>
                        @include('common.datepicker.datepicker_year',['model' => 'filters.time'])
                    </div>
                    <div class="col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <span x-data="{values: listStatus, model: filters.status, disabled: false,
                                init() {this.$watch('filters.status', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                        }"
                              @select-change="filters.status = $event.detail">
                            @include('common.select2.simple.select2_multiple', ['placeholder' => 'Chọn trạng thái'])
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-end form-group">
                    <div class="col-3">
                        <label class="tw-font-bold">Tên phụ lục</label>
                        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Nhập tên/mã phụ lục">
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Hợp đồng</label>
                        <span x-data="{
                                values: listContract, model: filters.contract_ids,
                                init() {this.$watch('filters.contract_ids', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                            }"
                              @select-change="filters.type = $event.detail"
                        >
                                @include('common.select2.extent.select2_multiple', [
                                    'placeholder' => 'Chọn hợp đồng'
                                ])
                        </span>
                    </div>
                    <div class="col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <span x-data="{values: listStatus, model: filters.status,
                                       init() {this.$watch('filters.status', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                        }" @select-change="filters.status = $event.detail">
                            @include('common.select2.simple.select2_multiple', [
                                'placeholder' => 'Chọn trạng thái'
                            ])
                        </span>
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Ngày ký</label>
                        @include('common.datepicker.datepicker', ['placeholder' => "Ngày ký", 'model' => "filters.signing_date"])
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Ngày hiệu lực</label>
                        @include('common.datepicker.datepicker', ['placeholder' => "Ngày hiệu lực", 'model' => "filters.from"])
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

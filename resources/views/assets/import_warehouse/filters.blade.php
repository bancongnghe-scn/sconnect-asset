<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end pb-4">
                <div class="col-2">
                    <label class="tw-font-bold">Tên/mã phiếu nhập</label>
                    <input class="form-control" type="text" x-model="filters.name_code" placeholder="Tên/mã phiếu nhập">
                </div>

                <div class="col-2">
                    <label class="tw-font-bold">Trạng thái</label>
                    <select class="form-select" x-model="filters.status">
                        <option value="">Chọn trạng thái</option>
                        <template x-for="(value, key) in LIST_STATUS_IMPORT_WAREHOUSE">
                            <option :value="key" x-text="value"></option>
                        </template>
                    </select>
                </div>

                <div class="col-2">
                    <label class="tw-font-bold">Người nhập</label>
                    <span x-data="{
                                    model: filters.created_by,
                                    init() {this.$watch('filters.created_by', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                                }" @select-change="filters.created_by = $event.detail">
                                    @include('common.select2.extent.select2', [
                                          'placeholder' => 'Người nhập',
                                          'values' => 'listUser',
                                ])
                    </span>
                </div>

                <div class="col-2">
                    <label class="tw-font-bold">Thời gian</label>
                    @include('common.datepicker.datepicker', ['placeholder' => "Thời gian", 'model' => "filters.created_at"])
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
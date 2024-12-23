<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row align-items-end form-group">
                    <div class="col-2">
                        <label class="tw-font-bold">Tên/số đơn hàng</label>
                        <input class="form-control" type="text" x-model="filters.code_name" placeholder="Tên/số đơn hàng">
                    </div>
                    <div class="col-2">
                        <label class="tw-font-bold">Ngày đơn hàng</label>
                        @include('common.datepicker.datepicker', ['placeholder' => "Ngày đơn hàng", 'model' => "filters.created_at"])
                    </div>
                    <div class="col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <div>
                            <select class="form-select" x-model="filters.status">
                                <option value="#">Chọn trạng thái</option>
                                <template x-for="(value, key) in LIST_STATUS_ORDER">
                                    <option :value="key" x-text="value"></option>
                                </template>
                            </select>
                        </div>
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

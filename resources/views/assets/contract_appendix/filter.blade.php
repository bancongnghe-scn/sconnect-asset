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
                        <select class="form-select select2" id="filterContract" multiple="multiple" data-placeholder="Chọn hợp đồng">
                            <template x-for="value in listContract" :key="value.id">
                                <option :value="value.id" x-text="value.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <select class="form-control select2" id="filterStatusAppendix" multiple="multiple" data-placeholder="Chọn trạng thái">
                            <template x-for="(value, key) in listStatus">
                                <option :value="key" x-text="value"></option>
                            </template>
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Ngày ký</label>
                        @include('common.datepicker', ['placeholder' => "Ngày ký", 'id' => "filterSigningDate"])
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Ngày hiệu lực</label>
                        @include('common.datepicker', ['placeholder' => "Ngày hiệu lực", 'id' => "filterFrom"])
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end tw-gap-x-4 form-group">
                <div class="col-3">
                    <label class="tw-font-bold">Tên mã/nhà cung cấp</label>
                    <input type="text" class="form-control" x-model="filters.code_name" placeholder="Nhập tên mã/nhà cung cấp">
                </div>
                <div class="col-3">
                    <label class="tw-font-bold">Ngành hàng</label>
                    <select class="form-control select2" multiple="multiple" id="industriesFilter" data-placeholder="Chọn ngành hàng ...">
                        <template x-for="industry in listIndustry" :key="industry.id">
                            <option :value="industry.id" x-text="industry.name"></option>
                        </template>
                    </select>
                </div>
                <div class="col-3">
                    <label class="tw-font-bold">Đánh giá</label>
                    <select class="form-control select2" multiple="multiple" id="statusFilter" data-placeholder="Chọn đánh giá ...">
                        <template x-for="(value, index) in status" :key="index">
                            <option :value="index" x-text="value"></option>
                        </template>
                    </select>
                </div>

                <div class="">
                    <button @click="getListSupplier(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                </div>
            </div>
        </div>
    </div>
</div>
